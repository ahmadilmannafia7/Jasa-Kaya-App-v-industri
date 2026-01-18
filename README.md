# JasaKaya - Sistem Kemitraan Kehutanan

Aplikasi web untuk mengelola kemitraan antara KTHR (Kelompok Tani Hutan Rakyat) dan PBPHH (Pemegang Izin Pemanfaatan Hasil Hutan) di Provinsi Jawa Timur. Platform ini memfasilitasi kolaborasi yang efektif antara petani hutan dan industri pengolahan hasil hutan untuk mendukung pengelolaan hutan berkelanjutan.

## Fitur Utama

### Dashboard KTHR (Kelompok Tani Hutan Rakyat)
- Manajemen profil dan informasi tegakan hutan
- Pengajuan dan pengelolaan permintaan kemitraan
- Monitoring kesepakatan kerjasama
- Penjadwalan dan manajemen pertemuan

### Dashboard PBPHH (Pemegang Izin Pemanfaatan Hasil Hutan)
- Eksplorasi dan pencarian KTHR berdasarkan lokasi dan jenis tanaman
- Pengelolaan kebutuhan material dan bahan baku
- Pengajuan permintaan kemitraan
- Monitoring kesepakatan dan kontrak kerjasama

### Dashboard CDK (Cabang Dinas Kehutanan)
- Approval dan verifikasi permintaan kemitraan
- Penjadwalan dan fasilitasi pertemuan
- Monitoring kemitraan di wilayah kerja
- Pelaporan dan analisis data kemitraan

### Dashboard Dinas Kehutanan
- Manajemen pengguna sistem
- Approval dan verifikasi PBPHH
- Monitoring kemitraan secara provinsi
- Laporan strategis dan analisis data

## Persyaratan Sistem

- PHP >= 8.1
- Composer
- Node.js >= 16.x
- NPM atau Yarn
- MySQL >= 8.0
- Web Server (Apache/Nginx)

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/username/JasaKaya-App.git
cd JasaKaya-App-v1
```

### 2. Install Dependencies PHP

```bash
composer install
```

### 3. Install Dependencies JavaScript

```bash
npm install
```

### 4. Konfigurasi Environment

```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Konfigurasi Database

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jasakaya_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 6. Buat Database

```bash
# Buat database MySQL
mysql -u root -p
CREATE DATABASE jasakaya_db;
exit
```

### 7. Jalankan Migration dan Seeder

```bash
# Jalankan migration
php artisan migrate

# Jalankan seeder
php artisan db:seed --class=RegionSeeder
php artisan db:seed --class=SuperAdminSeeder

# Atau jalankan semua seeder
php artisan db:seed
```

### 8. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 9. Jalankan Aplikasi

```bash
# Development server
php artisan serve

# Aplikasi akan berjalan di http://localhost:8000
```

## Akun Default

Setelah menjalankan seeder, Anda dapat login dengan akun berikut:

### Super Administrator
- **Email**: superadmin@dinas.go.id
- **Password**: password123

### Dinas Provinsi
- **Email**: kadis@dishut.jabar.go.id
- **Password**: password123

## Struktur Direktori

```
├── app/
│   ├── Http/Controllers/     # Controllers
│   ├── Models/               # Eloquent Models
│   └── Http/Middleware/      # Custom Middleware
├── database/
│   ├── migrations/           # Database Migrations
│   └── seeders/              # Database Seeders
├── resources/
│   ├── views/                # Blade Templates
│   ├── css/                  # Stylesheets
│   └── js/                   # JavaScript Files
├── routes/
│   └── web.php               # Web Routes
└── public/                   # Public Assets
```

## Pengembangan

### Menjalankan dalam Mode Development

```bash
# Terminal 1: Laravel development server
php artisan serve

# Terminal 2: Vite development server (hot reload)
npm run dev
```

### Menjalankan Queue (Opsional)

```bash
php artisan queue:work
```

### Menjalankan Scheduler (Opsional)

Tambahkan ke crontab:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Testing

```bash
# Jalankan unit tests
php artisan test

# Jalankan dengan coverage
php artisan test --coverage
```

## Deployment

### 1. Server Requirements

- PHP >= 8.1 dengan extensions: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Web Server (Apache/Nginx)

### 2. Deployment Steps

```bash
# 1. Upload files ke server
# 2. Install dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# 3. Set permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 4. Configure environment
cp .env.example .env
php artisan key:generate

# 5. Run migrations
php artisan migrate --force
php artisan db:seed --force

# 6. Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Web Server Configuration

#### Apache (.htaccess)

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

#### Nginx (nginx.conf)

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/your-project/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Akun
Berikut akun yang otomatis dibuat oleh seeder (kalau kamu menjalankan php artisan db:seed atau minimal RegionSeeder + SuperAdminSeeder).

DINAS_PROVINSI

Email: dinas.dishutjatim@gmail.com
Password: 12345678
Sumber: SuperAdminSeeder.php
KTHR_PENYULUH (untuk tiap kabupaten)

Email pattern: kthr.{region_code_lower}@jasakaya.id (contoh: kthr.mlg@jasakaya.id)
Password: password123
Sumber: KthrPbphhSeeder.php
PBPHH (untuk tiap kabupaten)

Email pattern: pbphh.{region_code_lower}@jasakaya.id (contoh: pbphh.mlg@jasakaya.id)
Password: password123
Sumber: KthrPbphhSeeder.php
CDK (kode wilayah tertentu)

Email pattern: cdk.{code_lower}@jasakaya.id (contoh: cdk.mlg@jasakaya.id)
Password: password123
Region codes yang dibuat: MLG, BJN, JBR, PCT, MDN, TGL, NGJ, LMJ, BWI, SNP
Sumber: CdkSeeder.php


## Kontribusi

Kontribusi sangat diterima! Silakan buat pull request atau laporkan issue jika Anda menemukan bug atau memiliki saran perbaikan.

## Lisensi

Hak Cipta © 2023 Dinas Kehutanan Provinsi Jawa Timur. Seluruh hak dilindungi.
