# OWS Laravel Project

A Laravel-based web application for uploading and processing Excel files.

## Requirements

- PHP >= 8.1
- Composer
- Node.js & npm
- MySQL or compatible database

## Installation

1. **Clone the repository:**
   ```sh
   git clone https://github.com/Sanjay-Office-Works/ows.git
   cd ows
   ```

2. **Install PHP dependencies:**
   ```sh
   composer install
   ```

3. **Install Node.js dependencies:**
   ```sh
   npm install
   ```

4. **Copy and configure environment:**
   ```sh
   cp .env.example .env
   # Edit .env with your database and mail settings
   ```

5. **Generate application key:**
   ```sh
   php artisan key:generate
   ```

6. **Run migrations:**
   ```sh
   php artisan migrate
   ```

7. **Build frontend assets:**
   ```sh
   npm run build
   ```

8. **Link storage (for file uploads):**
   ```sh
   php artisan storage:link
   ```

## Running Locally

- **Start the development server:**
  ```sh
  php artisan serve
  ```

- **Start the queue worker (for Excel imports):**
  ```sh
  php artisan queue:work --queue=imports
  ```

- **(Optional) Start Vite for hot-reloading:**
  ```sh
  npm run dev
  ```

## Folder Structure

```
app/
  Actions/            # Action classes (e.g., StoreUploadedFileAction)
  DTO/                # DTO classes (e.g., UploadedFileDTO)
  Events/             # Events (e.g., UploadedFileUpdated)
  Http/
    Controllers/      # Application controllers
    Requests/         # Form request validation (e.g., StoreUploadedFileRequest)
  Imports/            # Excel import logic (e.g., ProductImport)
  Jobs/               # Queue jobs (e.g., ProcessExcelFile)
  Models/             # Eloquent models
  Providers/          # Service providers
  Services/           # Services (e.g., UploadedFileService)
  View/               # View components

bootstrap/            # Laravel bootstrap files
config/               # Application configuration
database/
  factories/          # Model factories
  migrations/         # Database migrations
  seeders/            # Database seeders

public/               # Publicly accessible files (index.php, assets)
resources/
  css/                # CSS files
  js/                 # JavaScript files
  views/              # Blade templates

routes/               # Route definitions
storage/              # Logs, compiled files, user uploads
tests/                # Feature and unit tests
vendor/               # Composer dependencies
```

## Useful Commands

- Run tests:
  ```sh
  php artisan test
  ```
- Run Pest tests:
  ```sh
  ./vendor/bin/pest
  ```

---
