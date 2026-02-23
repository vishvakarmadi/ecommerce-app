# Ecommerce App (SSO Client)

## Developer: Aditya Vishvakarma

---

## About

Ecommerce application that uses **Foodpanda SSO** for authentication. Users login through the Foodpanda OAuth2 server — no separate registration needed.

## How SSO Works

1. User clicks **"Login with Foodpanda"** on the welcome page
2. Gets redirected to the Foodpanda app's OAuth authorization page
3. User logs in on Foodpanda and clicks **Authorize**
4. Foodpanda sends back an authorization code
5. Ecommerce app exchanges the code for an access token
6. Fetches user info from Foodpanda using the token
7. Creates/updates the user locally and logs them in

## Tech Stack

- Laravel 11 (PHP 8.2+)
- MySQL
- Bootstrap 5

## Setup

```bash
# clone and install
git clone https://github.com/vishvakarmadi/ecommerce-app.git
cd ecommerce-app
composer install

# configure
cp .env.example .env
php artisan key:generate
```

Update `.env` with:
```
DB_DATABASE=ecommerce_db
DB_USERNAME=root
DB_PASSWORD=

FOODPANDA_CLIENT_ID=ecommerce-client
FOODPANDA_CLIENT_SECRET=secret123abc
FOODPANDA_REDIRECT_URI=http://localhost:8001/sso/callback
FOODPANDA_URL=http://localhost:8000
```

```bash
# create database and run migrations
php artisan migrate

# start server on port 8001
php artisan serve --port=8001
```

> **Note:** The Foodpanda app must be running on port 8000 for SSO to work.

## Project Structure

```
app/
├── Http/Controllers/
│   └── SSOController.php      # handles OAuth flow
├── Models/
│   └── User.php
resources/views/
├── layouts/app.blade.php       # main layout
├── welcome.blade.php           # landing page with SSO button
└── dashboard.blade.php         # post-login dashboard
routes/
└── web.php                     # SSO routes
```
