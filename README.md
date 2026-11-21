# FlipPlay REST API

FlipPlay REST API adalah layanan katalog film dan serial serta koleksi media pribadi pengguna. Proyek ini menggunakan Laravel 12, Eloquent, dan Laravel API Resources.

## Menjalankan aplikasi

Pastikan PHP 8.2 atau lebih baru, Composer, MySQL, dan ekstensi `pdo_mysql` aktif. Dari root project jalankan:

```powershell
composer install
Copy-Item .env.example .env
```

Atur konfigurasi database di `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=flipplay_api
DB_USERNAME=root
DB_PASSWORD=
```

Buat database dan jalankan migrasi serta seeder:

```powershell
php -r "(new PDO('mysql:host=127.0.0.1;port=3306', 'root', ''))->exec('CREATE DATABASE IF NOT EXISTS flipplay_api');"
php artisan key:generate
php artisan config:clear
php artisan migrate:fresh --seed
php artisan serve
```

`migrate:fresh` menghapus tabel yang sudah ada pada database yang dikonfigurasi sebelum menjalankan migrasi. Server berjalan di `http://127.0.0.1:8000`. Seeder membuat tiga user dan sepuluh media contoh, termasuk media `Inception` dengan ID 1.

Jalankan pengujian dengan `php artisan test`.

## Endpoint

| Method | Path | Keterangan |
| --- | --- | --- |
| GET | `/api/media?title=...` atau `/api/media?search=...` | Daftar dan pencarian media |
| POST | `/api/media` | Tambah media |
| GET | `/api/media/{media}` | Detail media dan kolektornya |
| PUT/PATCH | `/api/media/{media}` | Perbarui media |
| DELETE | `/api/media/{media}` | Hapus media |
| GET | `/api/users` | Daftar user dan koleksi |
| GET | `/api/users/{user}` | Detail user dan koleksi |
| POST | `/api/users/{user}/collection` | Tambahkan media (`media_id`) ke koleksi |
| DELETE | `/api/users/{user}/collection/{media}` | Hapus media dari koleksi |

Validasi menghasilkan HTTP 422, penambahan media yang sudah ada pada koleksi menghasilkan HTTP 409, dan resource yang tidak ditemukan menghasilkan HTTP 404. Import `FlipPlay_API.postman_collection.json` ke Postman untuk skenario happy path dan failure path.
