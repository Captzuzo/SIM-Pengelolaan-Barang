## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

# Laravel 12 + Filament + Spatie Role Permission

Filament-Roles ini dibangun menggunakan **Laravel 12**, **Filament Admin Panel**, dan **Spatie Laravel Permission** untuk manajemen hak akses. Menggunakan **PHP 8.3.3** dan **MySQL** sebagai database utama.

---

## 🧰 Teknologi yang Digunakan

-   PHP 8.3.3
-   Laravel 12.x
-   MySQL
-   Filament v3 (Admin Panel)
-   Spatie Laravel-Permission
-   TailwindCSS (melalui Filament)
-   Eloquent ORM
-   Laravel Blade & Livewire

---

## 📦 Fitur Utama

-   Manajemen User
-   Role & Permission menggunakan Spatie
-   Panel Admin dengan Filament
-   Middleware berbasis role
-   Responsive UI dengan TailwindCSS

---

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/nama-kamu/nama-project.git
cd nama-project
```

## 2. Install Composer

composer install

## 3. Atur .env sesuai contoh menggunakan mysql

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=

## 4. Migrasi Database dan Seeder

php artisan migrate
php artisan db:seed

## 4. Jalankan Server

php artisan serve

## 5. Login

email : admin1@admin.com
password : password
