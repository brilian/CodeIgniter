# e-Rapor Module

Dokumen ini menjelaskan rancangan aplikasi e-Rapor berbasis CodeIgniter dan cara menjalankannya.

## Fitur Utama

- **Autentikasi & Peran**: Admin, Guru Mapel, Wali Kelas, dan Siswa dengan navigasi kontekstual.
- **Master Data**: Pengelolaan pengguna, mata pelajaran, kelas, siswa (termasuk import Excel), serta ekstrakurikuler.
- **Input Nilai**: Guru atau wali dapat membuat penilaian, mengisi nilai, dan mengunci/ membuka penilaian.
- **Rekap & Monitoring**: Wali/ admin melihat rekap rata-rata per mapel serta daftar siswa per kelas.
- **Ekstrakurikuler**: Pengaturan kegiatan dan penilaian ekstrakurikuler per siswa.
- **Rapor**: Lihat rapor di web, ekspor ke Excel, konversi ke PDF, atau gunakan template Excel kustom.
- **Template Engine**: Admin dapat mengunggah file `.xlsx` dan menjadikannya template utama untuk ekspor rapor.

## Dependensi

Tambahkan paket berikut melalui Composer:

```
phpoffice/phpspreadsheet
mpdf/mpdf
nesbot/carbon
```

Jika Composer atau PHP CLI belum tersedia di lingkungan Anda, install terlebih dahulu lalu jalankan:

```
composer install
```

> Catatan: Di lingkungan pengujian ini `composer` dan `php` belum tersedia sehingga instalasi paket tidak dapat dijalankan secara otomatis.

## Struktur Basis Data

Tabel inti yang perlu dibuat terdapat pada `database/schema.sql`, mencakup:

- `users`, `classes`, `subjects`, `students`
- `assessments`, `grades`
- `extracurriculars`, `extracurricular_scores`
- `report_templates`

Silakan import berkas tersebut ke database MySQL/MariaDB Anda, lalu sesuaikan `application/config/database.php`.

## Konfigurasi Penting

- `application/config/config.php`: sudah berisi `base_url` dinamis serta `encryption_key`.
- `application/config/erapor.php`: mengatur tahun ajaran default, skala predikat, dan direktori penyimpanan (`storage/*`).
- Direktori `storage/templates`, `storage/reports`, `storage/imports` digunakan untuk file unggahan/output (tersedia `.gitkeep`).

## Alur Kerja

1. **Login** menggunakan akun admin default (dibuat otomatis bila belum ada): `admin@erapor.test / admin123`.
2. **Kelola master data**: tambah guru/wali/siswa, mapel, kelas.
3. **Import siswa** (opsional) menggunakan menu `Siswa > Import` dan template Excel bawaan.
4. **Input nilai**: buat penilaian di menu `Input Nilai`, kemudian isi nilai per siswa.
5. **Isikan ekstrakurikuler** melalui menu `Ekstrakurikuler > Nilai`.
6. **Cetak rapor**: buka menu `Cetak Rapor`, pilih siswa, lalu unduh Excel/PDF.
7. **Konversi Excel → PDF**: gunakan tombol “Unduh PDF”; sistem terlebih dahulu membangkitkan lembar Excel (baik default maupun template kustom) kemudian mengekspor ke PDF menggunakan `PhpSpreadsheet` + `mPDF`.

## Penyesuaian Template

- Admin dapat mengunggah file `.xlsx` di menu `Template Rapor`.
- File disimpan ke `storage/templates`. Tandai template sebagai default agar dipakai pada menu ekspor.
- Jika template tidak ditemukan, sistem otomatis memakai layout Excel standar.

## Testing & Linting

- Jalankan `composer test` setelah dependensi terpasang.
- Untuk memastikan coding style, gunakan linter/CI bawaan CodeIgniter atau tool pihak ketiga sesuai kebutuhan.

## Catatan Tambahan

- Basis kode ini menggunakan layout Bootstrap 5 CDN sehingga tidak perlu aset lokal tambahan.
- Pastikan permission direktori `storage/*` dapat ditulis oleh PHP (misal `chmod -R 775 storage` di server Linux).
