# ServiceHub

ServiceHub is a Laravel-based web application designed to manage services, bookings, reviews, and notifications.

## Features
- **User Management**: Authentication and user profiles.
- **Services**: Browse and manage different services.
- **Bookings**: Users can book available services.
- **Reviews**: Leave reviews and ratings for services.
- **Notifications**: Stay updated with system notifications.

## Requirements

- PHP ^8.2
- Composer
- Node.js & NPM
- Database (MySQL, SQLite, etc.)

## Installation & Setup

1. **Install PHP dependencies**
   ```bash
   composer install
   ```

2. **Install frontend dependencies**
   ```bash
   npm install
   ```

3. **Set up Environment variables**
   Copy the `.env.example` file to `.env` and configure your database settings.
   ```bash
   cp .env.example .env
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Run Migrations**
   ```bash
   php artisan migrate
   ```

6. **Build frontend assets**
   ```bash
   npm run build
   ```
   *(For development, you can use `npm run dev`)*

## Running the Application

Start the local development server:

```bash
php artisan serve
```

Visit the application at `http://localhost:8000`.
