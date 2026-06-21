# 🏦 FuzzyKredit - Sistem Kelayakan Kredit (Fuzzy Tsukamoto 5C)

FuzzyKredit adalah aplikasi berbasis web yang dikembangkan menggunakan **Laravel** untuk membantu proses analisis kelayakan pemberian kredit. Sistem ini menggunakan metode **Fuzzy Tsukamoto** dan analisis **5C (Character, Capacity, Capital, Collateral, Condition)** untuk memberikan rekomendasi keputusan kredit yang akurat, objektif, dan otomatis.

---

## ✨ Fitur Utama

- **Analisis Kelayakan Kredit Otomatis**: Menggunakan logika Fuzzy Tsukamoto untuk menghitung skor dan persentase kelayakan nasabah secara dinamis.
- **Manajemen Kriteria 5C Terpadu**: Evaluasi yang komprehensif berdasarkan Karakter (SLIK OJK), Kapasitas Pembayaran (DSCR), Modal (Rasio Aset/Pinjaman), Agunan (LTV), dan Kondisi Ekonomi.
- **Workflow Persetujuan Bertingkat (Approval)**: Mengotomatisasi alur kerja mulai dari input Analis Kredit hingga *approval* oleh Kepala Cabang.
- **Multi-Role Authentication**: 
  - 👨‍💼 **Admin**: Mengelola pengguna, parameter fuzzy, *rule* (aturan), dan pengaturan batas konversi (*threshold*).
  - 🕵️‍♂️ **Analis Kredit**: Menginput data nasabah, memproses perhitungan fuzzy, dan membuat catatan rekomendasi analitis.
  - 👔 **Kepala Cabang**: Me-review hasil analisis dan memberikan persetujuan akhir (*approval*).
  - 📊 **Marketing**: Mengakses hasil akhir analisis yang telah disetujui untuk di-follow up.
- **Export PDF**: Kemudahan mengekspor Laporan Hasil Analisis ke dalam format dokumen PDF yang rapi dan siap cetak.
- **UI/UX Modern & Elegan**: Antarmuka responsif dan estetik dibangun menggunakan Vanilla CSS & Tailwind CSS styling.

---

## 🛠️ Teknologi yang Digunakan

- **Framework**: [Laravel 12.x](https://laravel.com/)
- **Bahasa Pemrograman**: PHP 8.2+
- **Database**: MySQL / MariaDB
- **Styling**: [Tailwind CSS](https://tailwindcss.com/)
- **Ikon**: FontAwesome 6
- **PDF Generator**: `barryvdh/laravel-dompdf`

---

## 🚀 Panduan Instalasi (Local Development)

Ikuti langkah-langkah di bawah ini untuk menjalankan FuzzyKredit di mesin lokal (localhost) Anda:

### Persyaratan Sistem
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL / MariaDB (XAMPP/Laragon)

### Langkah Instalasi

1. **Clone repositori**
   ```bash
   git clone https://github.com/username/fuzzy-kredit.git
   cd fuzzy-kredit
   ```

2. **Install dependensi PHP**
   ```bash
   composer install
   ```

3. **Install dependensi Node.js & Compile Asset**
   ```bash
   npm install
   npm run build
   ```

4. **Konfigurasi Environment**
   - Salin file `.env.example` menjadi `.env`:
     ```bash
     cp .env.example .env
     ```
   - Sesuaikan konfigurasi database Anda di dalam file `.env`:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=fuzzy_kredit
     DB_USERNAME=root
     DB_PASSWORD=
     ```

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Migrasi Database & Seeder**
   Pastikan Anda sudah membuat database kosong (misal: `fuzzy_kredit`), lalu jalankan:
   ```bash
   php artisan migrate:fresh --seed
   ```
   *(Perintah ini akan membuat semua struktur tabel sekaligus mengisi data awal (dummy) akun, parameter, rule fuzzy, dan parameter konversi).*

7. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```
   Aplikasi kini dapat diakses di **`http://localhost:8000`**.

---

## 🔑 Akun Uji Coba (Seeder)

Setelah melakukan *database seeding*, Anda dapat masuk (login) menggunakan akun default berikut:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Administrator** | `admin@fuzzykredit.com` | `password` |
| **Analis Kredit** | `analis@fuzzykredit.com` | `password` |
| **Kepala Cabang** | `kepcab@fuzzykredit.com` | `password` |
| **Marketing** | `marketing@fuzzykredit.com` | `password` |

---

## 📖 Alur Kerja Aplikasi (Workflow)

1. **Analis Kredit** membuat entri analisis baru untuk seorang calon nasabah dengan mengisikan rincian pendapatan, pinjaman, dan kondisi agunan (Parameter 5C).
2. Sistem secara otomatis akan menghitung tahap **Fuzzifikasi, Inferensi Rule, dan Defuzzifikasi (Weighted Average)** dari algoritma Tsukamoto untuk menghasilkan kesimpulan apakah nasabah **"Layak"** atau **"Tidak Layak"**.
3. Jika hasilnya **Tidak Layak**, laporan akan langsung disimpan tanpa butuh persetujuan.
4. Jika hasilnya **Layak**, laporan akan diteruskan dengan status *Menunggu Persetujuan*.
5. **Kepala Cabang** *login* dan melihat daftar analisis yang *menunggu*, meninjau rincian nilai 5C, kemudian membubuhkan tanda tangan persetujuan secara digital.
6. Setelah disetujui penuh, **Marketing** dapat melihat dan mengekspor hasilnya sebagai laporan final ke dalam file PDF.

---

## 📜 Lisensi

Aplikasi ini menggunakan lisensi *open-source* di bawah [MIT license](https://opensource.org/licenses/MIT). Silakan digunakan, dimodifikasi, dan didistribusikan secara bebas.
