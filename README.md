# Full E-commerce Store

> Online store with cart, checkout and admin panel

**Live demo:** https://ecommerce.freedev.app

## Screenshots

![Home](docs/screenshots/home.png)
![Products](docs/screenshots/products.png)
![Admin Dashboard](docs/screenshots/admin-dashboard.png)

## Features

- Home, product listing with search and category filter, product details
- Session cart, wishlist, checkout (Cash on Delivery, bank transfer, Telebirr)
- Register, login and order history
- Admin panel: dashboard stats, products CRUD with image upload, categories, orders and status updates
- CSRF protection on every form, PDO prepared statements

## Tech stack

PHP 8 · MySQL (PDO) · Bootstrap 5 · JavaScript

## Demo login (local install)

| Role | Email / user | Password |
|---|---|---|
| Admin | `admin@store.com` | `admin123` |

These are the default credentials of the seed data. The live demo's admin password is private.

## Run locally (XAMPP)

1. Copy this folder to `C:\xampp\htdocs\ecommerce`
2. Start **Apache** and **MySQL** in XAMPP
3. Open phpMyAdmin → **Import** → `database.sql`
4. Check the database settings in `includes/config.php` (default: host `localhost`, user `root`, empty password)
5. Open `http://localhost/ecommerce/`

### Deploying to shared hosting

Create an empty database in your hosting panel, delete the `CREATE DATABASE` / `USE` / `DROP DATABASE` lines at the top of `database.sql`, import it, then put the host's database name, user and password in `includes/config.php`.

## Notes

Portfolio / demo project. Before real production use add HTTPS, rate limiting, audit logs, backups and environment-based configuration.

## Author

Built by [natnaelameha213](https://github.com/natnaelameha213).
