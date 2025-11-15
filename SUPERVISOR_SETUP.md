# Setup Supervisor untuk Laravel Queue

## Konfigurasi Supervisor

File konfigurasi sudah dibuat: `email-sender-worker.conf`

### Instalasi Supervisor (jika belum terinstall)

```bash
sudo apt-get install supervisor
```

### Install Konfigurasi

1. Copy file konfigurasi ke direktori Supervisor:
```bash
sudo cp email-sender-worker.conf /etc/supervisor/conf.d/
```

2. Reload konfigurasi Supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
```

3. Start worker:
```bash
sudo supervisorctl start email-sender-worker:*
```

### Perintah Supervisor yang Berguna

- Cek status: `sudo supervisorctl status`
- Stop worker: `sudo supervisorctl stop email-sender-worker:*`
- Restart worker: `sudo supervisorctl restart email-sender-worker:*`
- Lihat log: `tail -f storage/logs/worker.log`

## Apakah Perlu Crontab?

**TIDAK PERLU** crontab untuk `queue:work` karena Supervisor sudah menjalankannya secara terus-menerus.

### Namun, Anda TETAP PERLU crontab untuk Laravel Scheduler:

Jika aplikasi Anda menggunakan Laravel Task Scheduling (commands yang dijadwalkan), tambahkan ini ke crontab:

```bash
crontab -e
```

Tambahkan baris ini:
```
* * * * * cd /home/teddybear/html/public/email-sender && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

## Perbedaan queue:work vs schedule:run

- **queue:work** (Supervisor):
  - Proses yang berjalan terus-menerus
  - Memproses job dari queue
  - Dikelola oleh Supervisor (auto-restart jika crash)

- **schedule:run** (Crontab):
  - Dijalankan setiap menit oleh cron
  - Menjalankan task yang dijadwalkan (jika ada)
  - Contoh: backup database harian, cleanup file lama, dll

## Penjelasan Konfigurasi

- `numprocs=2`: Menjalankan 2 worker secara parallel
- `--sleep=3`: Delay 3 detik jika tidak ada job
- `--tries=3`: Retry job 3x jika gagal
- `--max-time=3600`: Restart worker setiap 1 jam (mencegah memory leak)
- `autostart=true`: Auto start saat server boot
- `autorestart=true`: Auto restart jika crash

## Monitoring

Untuk memonitor queue jobs:

```bash
# Lihat failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all

# Clear failed jobs
php artisan queue:flush
```
