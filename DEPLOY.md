# Deploy Financial Planner ke Vercel + TiDB Cloud

## Prasyarat

- Akun [Vercel](https://vercel.com) (gratis)
- Akun [TiDB Cloud](https://tidbcloud.com) (gratis tier tersedia)
- [Git](https://git-scm.com/) terinstall
- [Vercel CLI](https://vercel.com/docs/cli) (opsional, bisa deploy via GitHub)

---

## Langkah 1: Setup TiDB Cloud Database

1. Buka https://tidbcloud.com dan buat akun
2. Klik **Create Cluster** → pilih **Serverless** (gratis)
3. Pilih region terdekat (misal: Singapore / Tokyo)
4. Setelah cluster aktif, klik **Connect** → pilih **General**
5. Catat informasi koneksi:
   - **Host**: `gateway01.ap-southeast-1.prod.aws.tidbcloud.com` (contoh)
   - **Port**: `4000`
   - **User**: `xxxxxxxx.root`
   - **Password**: (yang dibuat saat setup)
6. Download **CA Certificate** (akan digunakan untuk SSL)
7. Buat database baru:
   - Klik **SQL Editor** di TiDB Cloud dashboard
   - Jalankan: `CREATE DATABASE financial_planner;`

---

## Langkah 2: Setup Git Repository

```bash
cd c:\xampp\htdocs\financial-planner

# Inisialisasi git (jika belum)
git init
git add .
git commit -m "Initial commit - Financial Planner"
```

Push ke GitHub:
```bash
# Buat repo baru di GitHub, lalu:
git remote add origin https://github.com/USERNAME/financial-planner.git
git branch -M main
git push -u origin main
```

---

## Langkah 3: Deploy ke Vercel

### Opsi A: Via GitHub (Recommended)

1. Buka https://vercel.com/new
2. Import repository GitHub yang sudah dipush
3. Vercel akan otomatis mendeteksi `vercel.json`
4. Tambahkan **Environment Variables** (lihat Langkah 4)
5. Klik **Deploy**

### Opsi B: Via Vercel CLI

```bash
# Install Vercel CLI
npm i -g vercel

# Login
vercel login

# Deploy
cd c:\xampp\htdocs\financial-planner
vercel
```

---

## Langkah 4: Set Environment Variables di Vercel

Buka **Project Settings** → **Environment Variables** di Vercel dashboard.

Tambahkan variabel berikut:

| Variable | Value |
|----------|-------|
| `APP_NAME` | `Financial Planner` |
| `APP_ENV` | `production` |
| `APP_KEY` | `base64:LoXgoQpF1bzInJPcGf+RwXs1Z7uZmwcUX+2LPPYEzNE=` |
| `APP_DEBUG` | `false` |
| `APP_URL` | `https://your-app.vercel.app` |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | *(dari TiDB Cloud Connect dialog)* |
| `DB_PORT` | `4000` |
| `DB_DATABASE` | `financial_planner` |
| `DB_USERNAME` | *(dari TiDB Cloud, format: xxxx.root)* |
| `DB_PASSWORD` | *(password TiDB Cloud)* |
| `MYSQL_ATTR_SSL_CA` | `/etc/ssl/certs/ca-certificates.crt` |
| `SESSION_DRIVER` | `cookie` |
| `CACHE_STORE` | `array` |
| `LOG_CHANNEL` | `stderr` |

> **Catatan SSL**: Di Vercel (Linux), system CA certificates ada di `/etc/ssl/certs/ca-certificates.crt`. TiDB Cloud menggunakan public CA yang sudah termasuk di sana, jadi tidak perlu upload file CA terpisah.

> **Penting**: Generate APP_KEY baru untuk production dengan `php artisan key:generate --show`

---

## Langkah 5: Jalankan Migration

Karena Vercel serverless tidak bisa menjalankan `artisan migrate`, jalankan migration dari lokal ke TiDB Cloud:

```bash
cd c:\xampp\htdocs\financial-planner

# Temporary set env lokal ke TiDB Cloud
set DB_CONNECTION=mysql
set DB_HOST=gateway01.ap-southeast-1.prod.aws.tidbcloud.com
set DB_PORT=4000
set DB_DATABASE=financial_planner
set DB_USERNAME=xxxx.root
set DB_PASSWORD=your_password
set MYSQL_ATTR_SSL_CA=c:\path\to\ca-cert.pem

php artisan migrate --force
```

Atau bisa juga jalankan SQL langsung di **TiDB Cloud SQL Editor**:

```sql
CREATE TABLE `cashflow_reports` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nama` VARCHAR(255) NOT NULL,
    `bulan` VARCHAR(255) NOT NULL,
    `tahun` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
);

CREATE TABLE `cashflow_items` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `cashflow_report_id` BIGINT UNSIGNED NOT NULL,
    `category` VARCHAR(255) NOT NULL,
    `label` VARCHAR(255) NOT NULL,
    `amount` DECIMAL(15,2) DEFAULT 0,
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `cashflow_items_cashflow_report_id_foreign`
        FOREIGN KEY (`cashflow_report_id`) REFERENCES `cashflow_reports` (`id`)
        ON DELETE CASCADE
);

-- Juga buat tabel sessions (diperlukan Laravel)
CREATE TABLE `sessions` (
    `id` VARCHAR(255) PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `payload` LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    INDEX `sessions_user_id_index` (`user_id`),
    INDEX `sessions_last_activity_index` (`last_activity`)
);

-- Cache table
CREATE TABLE `cache` (
    `key` VARCHAR(255) PRIMARY KEY,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL
);

CREATE TABLE `cache_locks` (
    `key` VARCHAR(255) PRIMARY KEY,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL
);
```

---

## Langkah 6: Redeploy

Setelah migration selesai, redeploy di Vercel:

```bash
vercel --prod
```

Atau push ke GitHub dan Vercel akan auto-deploy.

---

## Troubleshooting

### Error "Could not find driver"
Vercel PHP runtime sudah include `pdo_mysql`. Pastikan `DB_CONNECTION=mysql`.

### Error SSL/TLS
Pastikan `MYSQL_ATTR_SSL_CA=/etc/ssl/certs/ca-certificates.crt` sudah di-set di environment variables Vercel.

### Error "View not found"
Pastikan `VIEW_COMPILED_PATH` sudah di-set ke `/tmp/views` (sudah dikonfigurasi di `vercel.json`).

### Session tidak persist
Session menggunakan cookie di Vercel (serverless tidak punya persistent filesystem). Ini sudah dikonfigurasi di `vercel.json`.

### Cold start lambat
Ini normal untuk serverless. Request pertama bisa 2-5 detik, request berikutnya akan cepat.

---

## Struktur File Deploy

```
financial-planner/
├── api/
│   └── index.php          ← Vercel serverless entry point
├── vercel.json             ← Vercel configuration
├── .vercelignore           ← Files to exclude from deploy
├── bootstrap/
│   └── app.php             ← Updated for /tmp storage on Vercel
└── ... (file Laravel lainnya)
```

---

## Development Lokal

Untuk development lokal tetap pakai SQLite seperti biasa:

```bash
php artisan serve --port=8080
```

Environment lokal (`.env`) tetap menggunakan `DB_CONNECTION=sqlite`.
Environment produksi (Vercel) menggunakan `DB_CONNECTION=mysql` via TiDB Cloud.
