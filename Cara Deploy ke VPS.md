# Cara Deploy ke VPS
### 1. Di mesin lokal – push ke Git
git add Dockerfile docker-compose.yml .dockerignore .env.docker docker/
git commit -m "chore: add Docker configuration"
git push

### 2. Di VPS – setup environment
git clone <repo-url>
cd uniqa-inventory

#### Buat .env dari template
cp .env.docker .env

#### Isi APP_KEY
php artisan key:generate --show
#### Isi JWT_SECRET
php artisan jwt:secret --show

#### Edit .env: isi DB_PASSWORD, APP_URL, data mail, dll.
nano .env

### 3. Di VPS – jalankan Docker
#### Build image dan jalankan semua service
docker compose up -d --build

#### Pantau log startup
docker compose logs -f app


Setelah startup selesai, migrasi database dan storage link dijalankan otomatis oleh entrypoint.sh. Aplikasi bisa diakses di http://IP-VPS-Anda.

Perintah operasional umum

#### Lihat status semua container
docker compose ps

#### Restart semua
docker compose restart

#### Lihat log salah satu service
docker compose logs -f nginx

#### Backup database PostgreSQL
docker compose exec db pg_dump -U postgres inventory_control > backup.sql

#### Update aplikasi setelah git pull
docker compose up -d --build