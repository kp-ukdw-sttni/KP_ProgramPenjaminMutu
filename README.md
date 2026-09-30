# Sistem Evaluasi Standar Mutu LPM STTNI

Sistem Evaluasi Standar Mutu merupakan platform digital terpusat yang dikembangkan untuk Lembaga Penjaminan Mutu (LPM) Sekolah Tinggi Teologi Nazarene Indonesia (STTNI). Sistem ini dirancang untuk memfasilitasi pelaksanaan Audit Mutu Internal (AMI) secara efektif, efisien, dan transparan.

Platform ini memungkinkan unit kerja (Auditee) untuk mengunggah laporan evaluasi capaian standar mutu, serta memfasilitasi tim penjaminan mutu (Auditor) untuk melakukan penilaian objektif, memberikan skor, dan memantau seluruh siklus tindakan perbaikan secara komprehensif.

---

## Fitur Utama

*   **Role-Based Access Control (RBAC)**
    Sistem menerapkan pemisahan hak akses (segregation of duties) yang ketat melalui Spatie Permission. Mendukung *multi-role assignments* dengan tingkatan:
    *   **Auditee**: Bertanggung jawab mengunggah evaluasi diri dan melakukan revisi.
    *   **Auditor**: Melakukan peninjauan dokumen, memberikan skor, dan merumuskan temuan audit.
    *   **Admin**: Mengelola pengaturan data master institut.
    *   **Superadmin**: Mengelola administrasi infrastruktur TI dan mengontrol sistem secara penuh.

*   **Document Lifecycle Management**
    Mekanisme otomasi alur kerja dokumen dengan sistem penguncian (locking) mutakhir. Mencegah modifikasi sepihak oleh Auditee setelah dokumen diserahkan. Siklus status dokumen meliputi: **Draft ➝ Menunggu Review ➝ Revisi ➝ Selesai**.

*   **Quantitative Scoring (Penilaian Kuantitatif)**
    Sistem penilaian menggunakan instrumen skala Likert (1 - 4) untuk mengukur tingkat pemenuhan standar mutu secara kuantitatif dan terukur.

*   **Dynamic Audit Findings & CAPA (Tindakan Koreksi & Pencegahan)**
    Sistem memiliki relasi *One-to-Many* yang dinamis untuk pencatatan temuan audit ganda pada setiap dokumen. Dilengkapi siklus pemantauan *Corrective and Preventive Action* (CAPA) komprehensif yang mewajibkan pengisian **Akar Masalah**, **Tindak Lanjut**, dan **Bukti Perbaikan** sebelum sebuah temuan dapat dinyatakan 'Ditutup' (Closed).

---

## Teknologi yang Digunakan

Sistem ini dikembangkan menggunakan tumpukan teknologi modern untuk menjamin stabilitas dan keamanan tingkat tinggi:

*   **Backend:** Laravel (PHP)
*   **Frontend:** Tailwind CSS, Alpine.js, Blade Templating
*   **Database:** MySQL / MariaDB

---

##  Panduan Instalasi dan Implementasi (Deployment)

Panduan berikut ditujukan bagi administrator sistem TI yang bertanggung jawab terhadap implementasi platform di lingkungan produksi (production environment).

### 1. Persiapan Basis Kode
Lakukan *clone* atau ekstrak kode sumber proyek ke direktori server Anda. Buka terminal/konsol dan jalankan perintah berikut untuk menginstal dependensi:

```bash
composer install --optimize-autoloader --no-dev
```

### 2. Konfigurasi Lingkungan (.env)
Salin berkas `.env.example` menjadi `.env` lalu sesuaikan konfigurasi *database* dengan basis data Anda:

```bash
cp .env.example .env
```

Pastikan Anda mengubah pengaturan kredensial database di dalam `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lpm_sttni
DB_USERNAME=root
DB_PASSWORD=password_database_anda
```

### 3. Persiapan Kriptografi dan Tautan Penyimpanan
Jalankan perintah berikut untuk menghasilkan *Application Key* dan menautkan direktori penyimpanan publik:

```bash
php artisan key:generate
php artisan storage:link
```

### 4. Instalasi Basis Data (CRITICAL)
Sistem ini sangat bergantung pada struktur data master yang telah dipersiapkan sebelumnya.
**PENGINGAT PENTING: DILARANG KERAS menjalankan `php artisan migrate:fresh --seed` pada lingkungan produksi untuk mencegah hilangnya data master vital.**

**Langkah yang wajib dilakukan:**
Buat database bernama `lpm_sttni` di MySQL/MariaDB, kemudian langsung lakukan import (restore) berkas SQL yang telah disediakan:
`evaluasi_standar_mutu_sttni.sql`

### 5. Optimalisasi Sistem
Bersihkan seluruh *cache* untuk memastikan performa yang maksimal dan memastikan konfigurasi terbaru terbaca:

```bash
php artisan optimize:clear
```
