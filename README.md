# Sistem Informasi Penjaminan Mutu Internal (e-SPMI)

Repositori ini memuat kode sumber untuk aplikasi e-SPMI (Sistem Informasi Penjaminan Mutu Internal). Aplikasi ini dirancang untuk mendigitalisasi siklus Penetapan, Pelaksanaan, Evaluasi, Pengendalian, dan Peningkatan (PPEPP) standar mutu pada perguruan tinggi. Sistem ini dikembangkan menggunakan kerangka kerja Laravel.

## Tim Pengembang

Berikut adalah susunan anggota tim pengembang yang diurutkan berdasarkan Nomor Induk Mahasiswa (NIM):

*   Benyamin Prasetya Putra Ramayana - 71230984
*   Putu Gde Kenzie Carlen Mataram - 71230994
*   Edrian Sepriadi Irawan - 71231011

## Fitur Utama

Arsitektur sistem ini mencakup beberapa modul inti yang dirancang sesuai dengan standar operasional penjaminan mutu:

*   **Autentikasi & Otorisasi:** Sistem keamanan berbasis peran (Role-Based Access Control) yang membatasi akses pengguna sesuai dengan wewenang masing-masing (Auditee, Auditor, Superadmin).
*   **Manajemen Standar Mutu:** Modul untuk menetapkan dan mengelola indikator mutu, dilengkapi dengan fitur integrasi data massal (Impor/Ekspor) menggunakan format Excel.
*   **Evaluasi Diri & Dokumen Mutu:** Modul pencatatan laporan pelaksanaan mutu yang memungkinkan pengunggahan bukti fisik.
*   **Audit Internal:** Modul untuk mencatat temuan audit, evaluasi kelengkapan dokumen, dan respons tindak lanjut.
*   **Keamanan Berkas Terisolasi:** Implementasi penyimpanan dokumen privat pada direktori `storage/app/private` untuk mencegah akses publik yang tidak sah terhadap berkas akreditasi.

## Persyaratan Sistem

*   PHP ^8.2
*   Composer
*   MySQL / MariaDB
*   Node.js & NPM (untuk kompilasi *asset* *frontend*)

## Panduan Instalasi

Jalankan perintah berikut pada terminal untuk mengonfigurasi proyek di lingkungan lokal:

1.  Kloning repositori dan masuk ke dalam direktori proyek:
    ```bash
    git clone <url-repositori>
    cd KP_ProgramPenjaminMutu-main/LPM
    ```
2.  Instalasi dependensi PHP menggunakan Composer:
    ```bash
    composer install
    ```
3.  Instalasi dan kompilasi dependensi *frontend*:
    ```bash
    npm install
    npm run build
    ```
4.  Salin fail konfigurasi *environment*:
    ```bash
    cp .env.example .env
    ```
5.  Konfigurasi koneksi basis data pada fail `.env`, kemudian buat *Application Key*:
    ```bash
    php artisan key:generate
    ```
6.  Jalankan migrasi basis data beserta *seeder* untuk mengisi data awal (pengguna admin, peran, dan program studi):
    ```bash
    php artisan migrate --seed
    ```
7.  Tautkan direktori penyimpanan publik:
    ```bash
    php artisan storage:link
    ```
8.  Jalankan server pengembangan lokal:
    ```bash
    php artisan serve
    ```

## Pengujian Sistem

Proyek ini dilengkapi dengan skenario pengujian otomatis menggunakan PHPUnit untuk memastikan integritas fitur dan keamanan. 

Jalankan perintah berikut untuk mengeksekusi seluruh pengujian (Termasuk pengujian untuk RBAC, Evaluasi Diri, Keamanan Berkas, dan Ekspor/Impor Excel):
```bash
php artisan test
```
