# Serve — README

About the project
- Serve is a small restaurant reservation platform that helps restaurants accept and manage bookings quickly. It pairs a Laravel backend with a Nuxt 3 frontend.
- Key features:
   - Create and manage reservations (date, time, party size, contact info, special requests)
   - Restaurant availability checks and simple conflict handling
   - Cookie-based authentication with Laravel Sanctum
   - PDF reservation confirmations (backend-side)

This README explains how to run the project locally (backend + frontend) for development.

Overview
- Backend: Laravel application located in `backend/`.
- Frontend: Nuxt 3 application located in `frontend/`.

Prerequisites
- Node.js (>=18 recommended)
- npm or pnpm (examples use npm)
- PHP 8.1+ (as required by Laravel in this repo)
- Composer (for PHP dependencies)
- SQLite (default) or other DB if you prefer
- Git

Quick start (recommended)
1. Clone the repository (already in your workspace):

   git clone <repo-url>

2. Backend — Laravel

- Change to the backend folder and install PHP dependencies:

```bash
cd backend
composer install
```

- Copy env example and set basic values (this project ships with `DB_CONNECTION=sqlite` by default):

```bash
cp .env.example .env
php artisan key:generate
```

- Run database migrations:

```bash
php artisan migrate 
```

- Run the Laravel development server (default port 8000):

```bash
php artisan serve
```

The backend will be available at http://localhost:8000 by default.

3. Frontend — Nuxt 3

- In a separate terminal, change to the frontend folder and install dependencies:

```bash
cd frontend
npm install
```


- Start the Nuxt dev server:

```bash
npm run dev
```

The frontend will be available at http://localhost:3000 by default.

