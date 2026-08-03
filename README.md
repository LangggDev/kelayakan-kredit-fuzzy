# FuzzyKredit - Sistem Kelayakan Kredit (Fuzzy Tsukamoto 5C)

FuzzyKredit adalah aplikasi berbasis web yang dikembangkan menggunakan **Laravel** untuk membantu proses analisis kelayakan pemberian kredit. Sistem ini menggunakan metode **Fuzzy Tsukamoto** dan analisis **5C (Character, Capacity, Capital, Collateral, Condition)** untuk memberikan rekomendasi keputusan kredit yang akurat, objektif, dan otomatis.

---

## Fitur Utama

- **Analisis Kelayakan Kredit Otomatis**: Menggunakan logika Fuzzy Tsukamoto untuk menghitung skor dan persentase kelayakan nasabah secara dinamis.
- **Manajemen Kriteria 5C Terpadu**: Evaluasi yang komprehensif berdasarkan Karakter (SLIK OJK), Kapasitas Pembayaran (DSCR), Modal (Rasio Aset/Pinjaman), Agunan (LTV), dan Kondisi Ekonomi.
- **Workflow Persetujuan Bertingkat (Approval)**: Mengotomatisasi alur kerja mulai dari input Analis Kredit hingga *approval* oleh Kepala Cabang.
- **Multi-Role Authentication**: 
  - **Admin**: Mengelola pengguna, parameter fuzzy, *rule* (aturan), dan pengaturan batas konversi (*threshold*).
  - **Analis Kredit**: Menginput data nasabah, memproses perhitungan fuzzy, dan membuat catatan rekomendasi analitis.
  - **Kepala Cabang**: Me-review hasil analisis dan memberikan persetujuan akhir (*approval*).
  - **Marketing**: Mengakses hasil akhir analisis yang telah disetujui untuk di-follow up.
- **Export PDF**: Kemudahan mengekspor Laporan Hasil Analisis ke dalam format dokumen PDF yang rapi dan siap cetak.