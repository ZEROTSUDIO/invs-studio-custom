# INVS Studio Custom - Sistem Manajemen Produksi & Penjadwalan

INVS Studio Custom adalah sebuah aplikasi sistem informasi manajemen berbasis web yang dibangun menggunakan **CodeIgniter**. Aplikasi ini dirancang khusus untuk mengelola alur kerja pesanan (orders), memantau status produksi, dan melakukan penjadwalan antrean produksi secara otomatis menggunakan algoritma **Shortest Job First (SJF)**.

## Fitur Utama

- **Manajemen Pesanan (Order Management):** Pencatatan pesanan masuk lengkap dengan detail pelanggan, tipe produk, kuantitas, dan estimasi waktu pengerjaan.
- **Penjadwalan Produksi Otomatis (Smart Scheduling):** Sistem mengurutkan antrean pekerjaan secara otomatis berdasarkan durasi pengerjaan terpendek *(Shortest Job First)* untuk memaksimalkan efisiensi waktu produksi.
- **Visualisasi Timeline (Gantt Chart):** Memantau jadwal produksi secara interaktif untuk melihat progres dan status setiap pekerjaan.
- **Admin Dashboard Integratif:** Ringkasan KPI statistik harian, daftar prioritas antrean produksi *(Today's Work Order)*, serta peringatan tenggat waktu 1 hari.
- **Sinkronisasi Status Real-time:** Status jadwal produksi secara otomatis tersinkronisasi dengan status pesanan.
- **Mode Simulasi (Demo Mode):** Fitur bypass yang mengizinkan pembaruan status dan pengurutan ulang antrean secara instan khusus untuk tujuan simulasi/demonstrasi tanpa harus menunggu penyelesaian produk di dunia nyata.

## Konsep Aplikasi

Aplikasi ini aslinya dikembangkan melalui refactor dan penyesuaian dari sistem manajemen sebelumnya, dengan penambahan fitur krusial agar sesuai dengan industri kreatif / perakitan custom seperti *INVS Studio*. Komponen intinya menghubungkan siklus hidup penuh mulai dari pembuatan order hingga masuk antrean penjadwalan.

## Persyaratan Sistem

- PHP 5.6 atau versi lebih baru (direkomendasikan PHP 7.x/8.x)
- Web Server (Apache / Nginx, seperti XAMPP atau Laragon)
- Database MySQL / MariaDB

## Instalasi & Menjalankan Aplikasi Lokal

1. Salin repositori ini ke dalam direktori root server lokal Anda (contoh `C:\laragon\www\invs-studio-custom` untuk Laragon atau direktori `htdocs` jika Anda menggunakan XAMPP).
2. Buat database MySQL baru, dan impor pengaturan data sistem yang berada di dalam folder (jika ada *dump-file* `.sql`).
3. Sesuaikan konfigurasi database pada file `application/config/database.php`.
4. Sesuaikan konstanta `base_url` pada `application/config/config.php` dengan URL lokal proyek (contoh `http://invs-studio-custom.test/` atau `http://localhost/invs-studio-custom/`).
5. Buka web browser kesayangan Anda dan jalankan aplikasi. 
6. (Opsional) Untuk mengakses mode Dashboard Admin, Anda perlu login menggunakan kredensial admin yang valid.
