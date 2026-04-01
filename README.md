# Directorio Comalcalco — REST API

> Hyperlocal business directory REST API for Comalcalco, Tabasco, México.  
> Built with **Laravel 11**, deployed to shared hosting via **GitHub Actions CI/CD**.

🌐 **Live app:** [app.directoriocomalcalco.jpcorelab.com](https://app.directoriocomalcalco.jpcorelab.com)  
📱 **Frontend (Flutter PWA):** [directoriocomalcalco_app](https://github.com/joseaperezcordova/directoriocomalcalco_app)

---

## 📌 About the Project

Directorio Comalcalco is a full-stack hyperlocal business directory for Comalcalco, Tabasco. It allows users to discover local businesses on an interactive map, filter by category, and view detailed business profiles.

The database was seeded with ~844 real businesses using the **Google Places API (Nearby Search + Text Search)**.

---

## 🚀 Tech Stack

| Layer       | Technology                          |
|-------------|--------------------------------------|
| Framework   | Laravel 11                           |
| Database    | MySQL                                |
| Auth        | Laravel Sanctum (token-based)        |
| Deployment  | Shared hosting (Hostinger) via FTP   |
| CI/CD       | GitHub Actions                       |
| Maps seed   | Google Places API (New)              |

---

## ✨ Key Features

- **Viewport-based business loading** — endpoint filters businesses by geographic bounding box (north/south/east/west), enabling efficient map rendering as the user pans
- **Category filtering** with dynamic Material Icons stored as icon codepoints in the database
- **Admin CRUD** — full create/read/update/delete for businesses and categories, with approval workflow
- **Tiered listings** — free, basic, and premium business tiers
- **CORS configured** for cross-origin consumption from the Flutter PWA frontend
- **Google Places API integration** — automated seeding scripts for bulk business import

---

## ⚙️ CI/CD Pipeline

Automated deployment to shared hosting on every push to `master`:

```
Push to master
    └── GitHub Actions
        ├── Setup PHP 8.4
        ├── composer install (--no-dev, --optimize-autoloader)
        ├── Deploy via FTP (SamKirkland/FTP-Deploy-Action)
        │   └── Excludes: .git, .env, node_modules, storage/logs
        └── Fix .htaccess permissions via lftp
```

Credentials managed via **GitHub Secrets** (no hardcoded values in repo).

---

## 📄 API Documentation

See [`APIDOC.md`](./APIDOC.md) for full endpoint reference including request/response examples.

---

## 🛠️ Local Setup

```bash
git clone https://github.com/joseaperezcordova/directoriocomalcalco_api.git
cd directoriocomalcalco_api

composer install
cp .env.example .env
php artisan key:generate

# Configure your DB in .env, then:
php artisan migrate
php artisan serve
```

---

## 📁 Project Structure

```
app/Http/Controllers/   # API controllers (businesses, categories, admin)
app/Http/Middleware/     # CORS and auth middleware
database/migrations/     # Schema migrations
routes/api.php           # All API routes
.github/workflows/       # GitHub Actions CI/CD pipeline
```

---

## 👤 Author

**José Antonio Pérez** — [@joseaperezcordova](https://github.com/joseaperezcordova)  
Freelance dev · Laravel + Flutter · [jpcorelab.com](https://jpcorelab.lemonsqueezy.com)
