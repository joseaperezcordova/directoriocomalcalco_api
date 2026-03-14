# Directorio Comalcalco — Documentación API

> Última actualización: 2026-03-14

---

## 1. Qué es el proyecto

API REST en **Laravel (PHP 8.4)** que sirve el directorio de negocios locales de Comalcalco.
Consume una app móvil en **Flutter**.

- **Repositorio:** `https://github.com/joseaperezcordova/directoriocomalcalco_api`
- **Rama principal:** `master`
- **Base de datos:** MariaDB 11.4 en servidor compartido — `jpcorela_directoriocomalcalco`
- **URL de producción:** configurada en `.env` del servidor (no en repo)

---

## 2. Estructura de carpetas principales

```
app/
  Http/
    Controllers/Api/
      AuthController.php          ← login, logout, me, change-password
      NegocioPublicoController.php ← endpoints públicos (categorías, negocios)
      NegocioCapturaController.php ← capturistas + admin (aprobar/rechazar)
      AdminController.php          ← CRUD usuarios, categorías, stats
    Middleware/
      AuthToken.php               ← middleware de autenticación propio
  Models/
    Usuario.php
    TokenSesion.php
    Negocio.php
    Categoria.php
    Horario.php
routes/
  api.php                         ← todas las rutas bajo /api
bootstrap/
  app.php                         ← registro de middlewares y CORS
.github/
  workflows/
    deploy-production.yml         ← CI/CD: push a master → FTP deploy
storage/
  app/public/negocios/{id}/       ← fotos de negocios
```

---

## 3. Endpoints completos

### Públicos (sin token)

| Método | Ruta | Descripción |
|--------|------|-------------|
| POST | `/api/auth/login` | Login por `username` o `email`. Devuelve `{ token, usuario }` |
| GET | `/api/categorias` | Lista categorías con `activo = 1`. Devuelve `id, nombre, icono` |
| GET | `/api/negocios` | Negocios aprobados. Params: `?q`, `?categoria_id`, `?domicilio=1`. Paginado 20 |
| GET | `/api/negocios/{id}` | Detalle de negocio con `categoria` y `horarios` |
| GET | `/api/test` | Health check |

### Protegidos — cualquier usuario autenticado (`auth.token`)

| Método | Ruta | Descripción |
|--------|------|-------------|
| POST | `/api/auth/logout` | Elimina token de `tokens_sesion` |
| GET | `/api/auth/me` | Datos del usuario autenticado |
| POST | `/api/auth/change-password` | Cambia contraseña. Body: `{ password_actual, password_nuevo }` |
| GET | `/api/captura/negocios` | Negocios del capturista (admin ve todos). Param: `?estado` |
| POST | `/api/captura/negocios` | Crea negocio (`estado=pendiente`) + array `horarios` |
| PUT | `/api/captura/negocios/{id}` | Edita negocio + horarios (capturista solo los suyos y no aprobados) |
| POST | `/api/captura/negocios/{id}/foto` | Sube foto. Campo multipart: `foto`. Max 2MB |

### Solo admin (`auth.token:admin`)

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/admin/negocios` | Todos los negocios. Param: `?estado`. Paginado 20 |
| POST | `/api/admin/negocios/{id}/aprobar` | Estado → `aprobado`. Guarda `aprobado_por` y `aprobado_at` |
| POST | `/api/admin/negocios/{id}/rechazar` | Estado → `rechazado`. Body opcional: `{ motivo }` |
| GET | `/api/admin/stats` | Estadísticas por capturista: total, aprobados, pendientes, rechazados |
| GET | `/api/admin/usuarios` | Lista todos los usuarios |
| POST | `/api/admin/usuarios` | Crea usuario. Body: `{ nombre, username, email, password, rol }` |
| PUT | `/api/admin/usuarios/{id}` | Edita usuario. `password` opcional |
| DELETE | `/api/admin/usuarios/{id}` | Desactiva usuario (`activo = 0`), no elimina físicamente |
| GET | `/api/admin/categorias` | Lista todas las categorías (activas e inactivas), ordenadas por nombre |
| POST | `/api/admin/categorias` | Crea categoría. Body: `{ nombre, icono }` |
| PUT | `/api/admin/categorias/{id}` | Edita categoría. Body: `{ nombre, icono }` |
| PATCH | `/api/admin/categorias/{id}/toggle` | Alterna `activo` (1→0, 0→1). Devuelve `{ id, activo }` |

---

## 4. Modelos y tablas

### `Usuario` → tabla `usuarios`
```
id, nombre, username (unique, nullable), email (unique),
password, rol (admin|capturista), activo (bool)
```
- `$hidden = ['password']`
- Métodos: `esAdmin()`, `esCapturista()`
- Relaciones: `hasMany(TokenSesion)`, `hasMany(Negocio)` via `capturado_por`

### `TokenSesion` → tabla `tokens_sesion`
```
id, usuario_id (FK), token (unique, varchar 255), expira_at (timestamp), created_at
```
- `$casts: expira_at → datetime`
- Relaciones: `belongsTo(Usuario)`
- ⚠️ Columna se llama `expira_at` (NO `expira_en`)

### `Negocio` → tabla `negocios`
```
id, nombre, categoria_id (FK), telefono, whatsapp, facebook, instagram,
direccion, colonia, referencia, lat, lng, descripcion, foto_url,
servicio_domicilio (bool), plan (gratis|basico|premium), plan_vence_at,
estado (pendiente|aprobado|rechazado), motivo_rechazo (text, nullable),
activo (bool), capturado_por (FK usuarios), aprobado_por (FK usuarios),
aprobado_at, created_at, updated_at
```
- Scope: `scopePublicos()` → filtra `estado = aprobado`
- Relaciones: `belongsTo(Categoria)`, `hasMany(Horario)`, `belongsTo(Usuario, capturado_por)` como `capturista`

### `Categoria` → tabla `categorias`
```
id, nombre, icono (varchar 50, nombre del icono ej: store, medical),
activo (bool), created_at
```
- `public $timestamps = false` (la tabla NO tiene `updated_at`)
- Relaciones: `hasMany(Negocio)`

### `Horario` → tabla `horarios`
```
id, negocio_id (FK), dia (enum: lunes..domingo), hora_apertura (time),
hora_cierre (time), cerrado (bool)
```
- Unique: `(negocio_id, dia)`

### Vista en BD (no usada por API aún)
- `v_negocios_abiertos` — negocios aprobados y abiertos en el horario actual

---

## 5. Autenticación

**Sin Sanctum ni Passport — token propio en tabla `tokens_sesion`.**

**Login:**
```
POST /api/auth/login
Body: { "username": "admin o email@ejemplo.com", "password": "..." }
```
- Busca usuario por `username` OR `email` en el campo `username`
- Verifica con `Hash::check()`
- Genera `Str::random(64)`, lo guarda en `tokens_sesion` con `expira_at = now() + 30 días`
- Devuelve `{ token, usuario: { id, nombre, username, email, rol } }`

**Uso del token en requests:**
```
Authorization: Bearer {token}
// o alternativo:
X-Auth-Token: {token}
```

**Logout:** elimina el registro de `tokens_sesion`. Las sesiones anteriores del mismo usuario siguen válidas hasta que expiren.

---

## 6. Middlewares

### `AuthToken` (`auth.token`)
- Lee token del header `Authorization: Bearer` o `X-Auth-Token`
- Busca en `tokens_sesion` donde `token = ? AND expira_at > NOW()`
- Verifica que `usuario.activo = true`
- Inyecta el usuario en `$request->attributes` como `_usuario`
- Sin token: 401 `{ message: 'Token requerido' }`
- Token inválido/expirado: 401 `{ message: 'Token inválido o expirado' }`
- Usuario inactivo: 403 `{ message: 'Usuario inactivo' }`

### `AuthToken` con parámetro rol (`auth.token:admin`)
- Mismo comportamiento + verifica `usuario.rol === 'admin'`
- Sin rol: 403 `{ message: 'Sin permisos suficientes' }`

### `HandleCors`
- Registrado en `bootstrap/app.php` para todas las rutas `api/*`
- Config en `config/cors.php`: `allowed_origins = ['*']`, `allowed_methods = ['*']`

---

## 7. Decisiones técnicas importantes

### Storage de fotos
- `POST /api/captura/negocios/{id}/foto` sube al disco `public`
- Ruta: `storage/app/public/negocios/{id}/`
- Campo en BD: `foto` (no `foto_url`) — el modelo tiene `foto` en fillable
- Validación: `image|max:2048` (2MB)

### Búsqueda FULLTEXT
- En `GET /api/negocios?q=texto`
- Si `strlen($q) < 3` → `LIKE %texto%` en `nombre`
- Si `strlen($q) >= 3` → `MATCH(nombre, descripcion) AGAINST(? IN BOOLEAN MODE)`
- Índice FULLTEXT en BD sobre `(nombre, descripcion, direccion, colonia)`

### Ordenamiento por plan
- Negocios públicos se ordenan: `premium → basico → gratis`
- Query: `orderByRaw("FIELD(plan, 'premium', 'basico', 'gratis')")`

### Horarios
- Se guardan/reemplazan en bloque al crear o editar un negocio
- En `PUT` se hace `delete()` de todos y se reinsertan
- Constraint unique `(negocio_id, dia)` garantiza un horario por día

### Roles
- Solo dos roles: `admin` y `capturista`
- Capturista solo edita sus propios negocios y solo si están `pendiente` o `rechazado`
- Admin ve y edita todo

---

## 8. Pendiente / en progreso

- [ ] Endpoint `GET /api/negocios?abierto=1` — la vista `v_negocios_abiertos` existe en BD pero no está integrada en la API
- [ ] `foto_url` vs `foto` — revisar consistencia del nombre del campo entre modelo y BD
- [ ] Paginación en `GET /api/admin/categorias` (actualmente devuelve todo sin paginar)
- [ ] Notificaciones push al capturista cuando su negocio es aprobado/rechazado
- [ ] El campo `plan_vence_at` existe en BD pero no se gestiona desde la API

---

## 9. Deploy — CI/CD

**Trigger:** cualquier push a `master`

**Pipeline (GitHub Actions):**
1. Checkout del repo
2. Setup PHP 8.4 con extensiones necesarias
3. `composer install --no-dev --optimize-autoloader`
4. Upload por FTP usando `SamKirkland/FTP-Deploy-Action@v4.3.4`
5. Fix de permisos `.htaccess` y `public/` vía `lftp`

**Excluidos del deploy:**
- `.git`, `.env`, `storage/logs/`, `node_modules/`

**Secrets en GitHub Actions:**
- `FTP_SERVER_PROD`, `FTP_USERNAME_PROD`, `FTP_PASSWORD_PROD`, `FTP_SERVER_DIR_PROD`

**Nota importante:** el archivo `.env` NO se sube por FTP — debe existir manualmente en el servidor. Los logs de Laravel (`storage/logs/`) tampoco se sincronizan, por eso el log local siempre aparece vacío.

---

## Coordinación con app Flutter

El proyecto Flutter (`joseaperezcordova/directoriocomalcalco_app`) es mantenido en una sesión separada de Claude. Los cambios de contrato de la API (campos nuevos, endpoints nuevos) deben comunicarse a esa sesión. Regla acordada: **no sobreescribir archivos existentes sin confirmar con el usuario primero**, ya que los cambios se coordinan entre ambas sesiones.
