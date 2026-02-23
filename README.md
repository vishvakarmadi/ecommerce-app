# Ecommerce App (SSO Client)

This is a Laravel app that uses Foodpanda as its login provider via OAuth2 SSO.
Users don't register here — they login through Foodpanda and get auto-authenticated.

## How SSO Works

1. User visits ecommerce app and clicks "Login with Foodpanda"
2. They get redirected to Foodpanda's login page
3. After logging in on Foodpanda, user clicks "Authorize"
4. Foodpanda sends an auth code back to ecommerce app
5. Ecommerce app exchanges the code for an access token
6. It fetches user info using the token, creates/updates the local user, and logs them in

This is standard OAuth2 Authorization Code flow.

## Tech Stack

- Laravel 11
- Bootstrap 5
- MySQL

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database credentials:
```
DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_pass
```

Also set the Foodpanda SSO config in `.env`:
```
FOODPANDA_URL=http://localhost:8000
FOODPANDA_CLIENT_ID=ecommerce-client
FOODPANDA_CLIENT_SECRET=secret123abc
FOODPANDA_REDIRECT_URI=http://localhost:8001/sso/callback
```

Run migrations:
```bash
php artisan migrate
```

Start the server:
```bash
php artisan serve --port=8001
```

## Login

Go to http://localhost:8001, click "Login with Foodpanda".
Use the demo credentials on Foodpanda: `test@example.com` / `12345678`

## Live Demo

- URL: https://ecommerce-app.kisusoft.com
- Login via: https://foodpanda-app.kisusoft.com
- Credentials: test@example.com / 12345678
