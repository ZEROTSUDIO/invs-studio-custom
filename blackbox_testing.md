# BAB IV/V: PENGUJIAN SISTEM (BLACKBOX TESTING)

Bab ini menyajikan hasil pengujian fungsionalitas sistem informasi manajemen produksi dan penjadwalan **INVS Studio Custom** yang dibangun menggunakan framework CodeIgniter. Tujuan utama pengujian ini adalah memastikan bahwa seluruh modul dan fitur aplikasi berjalan sesuai dengan spesifikasi kebutuhan yang telah didefinisikan sebelumnya tanpa memperhatikan struktur internal kode program (pengujian fungsional).

---

## 1. Metodologi Pengujian
Metode pengujian yang diterapkan dalam sistem ini adalah **Blackbox Testing** (Pengujian Kotak Hitam). Metode ini berfokus pada hasil masukan (input) dan keluaran (output) sistem untuk memastikan kecocokan dengan fungsionalitas yang diharapkan.

Teknik spesifik yang digunakan dalam pengujian ini meliputi:
1. **Equivalence Partitioning (EP)**: Membagi domain input dari program ke dalam kelas-kelas data (valid dan invalid) untuk merancang kasus uji yang efisien.
2. **Boundary Value Analysis (BVA)**: Menguji nilai batas maksimum, minimum, atau di sekitar batas domain masukan sistem untuk meminimalkan potensi kegagalan logika batas (contohnya batas ukuran berkas, nilai durasi, dan tanggal tenggat waktu).

---

## 2. Lingkungan Pengujian
Pengujian dilaksanakan pada lingkungan perangkat lunak dan perangkat keras sebagai berikut:

| Komponen | Spesifikasi Lingkungan Pengujian |
| :--- | :--- |
| **Sistem Operasi** | Windows 10/11 Home / Pro |
| **Web Server** | Apache (XAMPP / Laragon) |
| **Bahasa Pemrograman** | PHP 7.4 / PHP 8.x |
| **Database Management System** | MariaDB / MySQL 5.7+ |
| **Web Browser** | Google Chrome (Versi Terbaru) |
| **Alat Bantu Pengujian** | Chrome DevTools (untuk inspeksi AJAX & Respons JSON) |

---

## 3. Skenario dan Tabel Kasus Uji (Test Cases)

Berikut adalah daftar kasus uji fungsionalitas yang mencakup seluruh modul utama pada sistem informasi INVS Studio Custom:

### MODUL 1: AUTENTIKASI PENGGUNA (LOGIN & LOGOUT)
Fokus pengujian ini adalah memvalidasi keamanan hak akses admin, keabsahan kredensial, dan penanganan session.

| ID Kasus Uji | Skenario Pengujian | Kelas Input (EP/BVA) | Hasil yang Diharapkan | Hasil Aktual | Status |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **TC-AUTH-01** | Mengakses halaman Dashboard secara langsung tanpa login terlebih dahulu via URL (`/dashboard`) | Akses Tidak Sah | Sistem mendeteksi ketiadaan session, mencegah akses, dan mengalihkan halaman (*redirect*) kembali ke `/login?alert=belum_login`. | Sesuai Harapan | **PASS** |
| **TC-AUTH-02** | Mengosongkan isian email dan password pada form login lalu menekan tombol "Login" | Input Kosong (EP) | Validasi form aktif, menampilkan pesan error bahwa email dan password wajib diisi, tetap di halaman login. | Sesuai Harapan | **PASS** |
| **TC-AUTH-03** | Memasukkan format email yang salah (misal: `admin#invs.com`) dan password sembarang | Format Email Invalid (EP) | Browser/sistem menolak form submit karena format email tidak sesuai standar penulisan email. | Sesuai Harapan | **PASS** |
| **TC-AUTH-04** | Memasukkan email yang terdaftar namun password salah (misal: `admin@invs.com` / `pass123_salah`) | Password Salah (EP) | Sistem melakukan pengecekan dengan enkripsi MD5, mencocokkan ke tabel `users`, menolak akses, dan mengalihkan ke `/login?alert=gagal`. | Sesuai Harapan | **PASS** |
| **TC-AUTH-05** | Memasukkan email dan password yang valid dan sesuai di database | Kredensial Valid (EP) | Sistem membuat session login (`id`, `name`, `email`, `status = telah_login`) dan mengalihkan halaman ke `/dashboard`. | Sesuai Harapan | **PASS** |
| **TC-AUTH-06** | Menekan tombol "Logout" atau mengakses URL `/dashboard/keluar` saat dalam kondisi login | Penghancuran Session | Sistem menghancurkan session aktif (`sess_destroy`) dan mengalihkan pengguna kembali ke halaman utama `/login`. | Sesuai Harapan | **PASS** |

---

### MODUL 2: PEMESANAN MANDIRI (HOMEPAGE PELANGGAN)
Fokus pengujian adalah memvalidasi input pesanan mandiri, integrasi perhitungan durasi berdasarkan kategori produk, validasi asinkron (AJAX) tanggal deadline teraman, serta pencegahan bentrok antrean di backend.

| ID Kasus Uji | Skenario Pengujian | Kelas Input (EP/BVA) | Hasil yang Diharapkan | Hasil Aktual | Status |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **TC-CUST-01** | Mengisi form pemesanan mandiri namun mengosongkan nama pelanggan dan nomor telepon | Input Kosong (EP) | Validasi formulir sisi klien menolak pengiriman data dan meminta pengguna melengkapi bidang wajib. | Sesuai Harapan | **PASS** |
| **TC-CUST-02** | Memilih kategori produk (misal: Sablon Plastisol) dan mengisi jumlah kuantitas ukuran kaos | Perubahan Kuantitas | Sistem menghitung total kuantitas secara real-time dan menampilkan estimasi durasi produksi (menit) berdasarkan standar durasi per unit di tabel `categories`. | Sesuai Harapan | **PASS** |
| **TC-CUST-03** | Memicu AJAX check earliest deadline dengan mengirim durasi estimasi `0` atau minus (`est_duration <= 0`) | Nilai Batas Invalid (BVA) | Endpoint `/home/earliest_deadline` mengembalikan respons JSON berupa pesan kesalahan `{'earliest_date': '', 'error': 'Invalid duration'}`. | Sesuai Harapan | **PASS** |
| **TC-CUST-04** | Mengirim permintaan AJAX earliest deadline dengan estimasi durasi valid (misal: 480 menit) | Nilai Valid (EP) | Sistem merespons dengan JSON berisi tanggal rekomendasi tercepat yang aman berdasarkan antrean aktif dan waktu kerja, e.g., `{'earliest_date': 'YYYY-MM-DD', ...}`. | Sesuai Harapan | **PASS** |
| **TC-CUST-05** | Memilih tanggal deadline yang lebih awal dari `earliest_date` yang disarankan sistem lalu submit | Nilai Batas Melanggar (BVA) | Validasi backend mendeteksi pelanggaran (`deadline < earliest_date`). Pendaftaran ditolak dan diarahkan ke `?alert=deadline_conflict#order`. | Sesuai Harapan | **PASS** |
| **TC-CUST-06** | Mengunggah berkas desain dengan ukuran melebihi 10 Megabyte (misal: 12MB) | Nilai Batas Invalid (BVA) | Pustaka `upload` CodeIgniter menolak berkas karena melebihi batas konfigurasi `max_size` (10240 KB), file tidak tersimpan di folder `./gambar/orders/`. | Sesuai Harapan | **PASS** |
| **TC-CUST-07** | Mengunggah berkas dengan format di luar izin (misal: `.exe` atau `.txt`) | Ekstensi Invalid (EP) | Pustaka `upload` menolak berkas karena tipe berkas tidak cocok dengan `allowed_types` (`jpg|jpeg|png|gif|pdf|ai|psd|zip`). | Sesuai Harapan | **PASS** |
| **TC-CUST-08** | Mengisi seluruh data form dengan benar, mengunggah berkas desain valid, serta memilih deadline yang aman | Input Valid Lengkap (EP) | Sistem memeriksa apakah nomor telepon pelanggan sudah ada di DB. Jika baru, sistem membuat pelanggan baru, menyimpan order dengan status `ordered`, menyimpan item ukuran, lalu mengalihkan ke `?alert=success#order`. | Sesuai Harapan | **PASS** |

---

### MODUL 3: MANAJEMEN PESANAN (DASHBOARD ADMIN)
Fokus pengujian adalah memvalidasi aksi admin saat menambahkan pesanan baru, mencari data pelanggan yang ada secara dinamis, melakukan perubahan data pesanan aktif, serta batasan edit untuk status tertentu.

| ID Kasus Uji | Skenario Pengujian | Kelas Input (EP/BVA) | Hasil yang Diharapkan | Hasil Aktual | Status |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **TC-ORD-01** | Melakukan pencarian pelanggan secara instan via AJAX pada halaman tambah pesanan dengan mengetikkan 1 karakter | Nilai Minimum (BVA) | Fitur autofill AJAX mengirim kueri dan mengembalikan data list nama pelanggan yang sesuai dari tabel `customers` secara dinamis. | Sesuai Harapan | **PASS** |
| **TC-ORD-02** | Menambahkan pesanan baru dari dashboard admin dengan memilih pelanggan yang belum terdaftar di daftar pilihan | Customer Baru Inline | Admin mengisi form tambah pelanggan baru secara pop-up/inline, AJAX memicu fungsi `customer_create` yang menyisipkan data ke DB dan mengembalikan `customer_id` baru untuk dilampirkan pada formulir order. | Sesuai Harapan | **PASS** |
| **TC-ORD-03** | Menyimpan pesanan dari dashboard admin dengan data valid, namun tanpa menetapkan ID pelanggan | Ketiadaan Data Kunci | Sistem menolak proses penyimpanan di fungsi `save_order` dan mengalihkan halaman ke `/dashboard/new_order?alert=no_customer`. | Sesuai Harapan | **PASS** |
| **TC-ORD-04** | Membuka halaman edit pesanan untuk pesanan yang sudah berstatus 'done' (selesai) atau 'canceled' (batal) | Status Terlarang (EP) | Sistem mendeteksi status terlarang di `edit_order($id)`, menolak akses halaman form, dan mengalihkan admin ke `/dashboard/orders?alert=invalid_action`. | Sesuai Harapan | **PASS** |
| **TC-ORD-05** | Mengubah detail data pesanan berstatus 'scheduled' (kategori, kuantitas, estimasi durasi, atau deadline) | Update & Re-schedule | Sistem memperbarui tabel `orders` dan tabel `order_items` via transaksi database. Di akhir proses, sistem memicu fungsi `M_schedule::generate()` untuk menyusun ulang jadwal produksi secara otomatis. | Sesuai Harapan | **PASS** |
| **TC-ORD-06** | Melakukan aksi pembatalan pesanan berstatus aktif ('waiting'/'scheduled') | Pembatalan Pesanan | Sistem menjalankan transaksi aman: mengubah status order menjadi `canceled`, menghapus baris terkait di tabel `production_schedule`, memicu `M_schedule::generate()` untuk memajukan jadwal antrean berikutnya, lalu kembali ke daftar pesanan. | Sesuai Harapan | **PASS** |

---

### MODUL 4: PEMBARUAN STATUS PRODUKSI & SINKRONISASI REAL-TIME
Fokus pengujian ini adalah memvalidasi integritas perubahan status pesanan dan dampaknya secara langsung (Live-Sync) terhadap urutan antrean jadwal produksi.

| ID Kasus Uji | Skenario Pengujian | Kelas Input (EP/BVA) | Hasil yang Diharapkan | Hasil Aktual | Status |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **TC-STAT-01** | Mengubah status pesanan menjadi nilai status yang tidak didefinisikan (misal: `/dashboard/update_status/5/deleted_status`) | Nilai Status Invalid | Sistem membatasi lewat pencocokan array `$allowed_orders_statuses` dan langsung mengalihkan admin ke `/dashboard/orders` tanpa mengubah database. | Sesuai Harapan | **PASS** |
| **TC-STAT-02** | Mengubah status pesanan menjadi `in_progress` dari halaman daftar pesanan admin | Transisi Aktif (EP) | Status pesanan diperbarui menjadi `in_progress`. Database memperbarui status jadwal produksi menjadi `in_progress` dan waktu `start_date` disinkronisasikan ke waktu saat ini (*now* Clamped ke jam operasional) guna mencegah 'future queue bug'. | Sesuai Harapan | **PASS** |
| **TC-STAT-03** | Mengubah status pesanan aktif menjadi `done` (selesai) sebelum durasi estimasi berakhir di dunia nyata | Penyelesaian Cepat | Status pesanan diubah ke `done`, posisi antrean diset ke `0`, dan kolom `end_date` diperbarui ke waktu sekarang. Sistem memicu regenerasi jadwal agar sisa pekerjaan lain bergeser maju mengisi kekosongan waktu secara instan. | Sesuai Harapan | **PASS** |

---

### MODUL 5: ALGORITMA PENJADWALAN MULTI-TIER
Fokus pengujian ini adalah memvalidasi ketepatan pemisahan pesanan ke dalam 3 tier penjadwalan (Quick-Insert, Urgent, Normal) serta kebenaran logika pengurutan (SJF & EDF) saat tombol "Generate Schedule" ditekan.

| ID Kasus Uji | Skenario Pengujian | Logika Kasus Uji | Hasil yang Diharapkan | Hasil Aktual | Status |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **TC-ALG-01** | Memasukkan pesanan dengan estimasi durasi kecil (≤ threshold) dan deadline dekat (tenggat ≤ hari ini + N hari) yang muat di sisa jam kerja hari ini | Kriteria Uji Tier **Quick-Insert** | Sistem mengklasifikasikan pesanan ke dalam Tier Quick-Insert. Pesanan dimasukkan dalam antrean prioritas awal yang dimulai dari waktu sekarang (bypass antrean normal) dan diurutkan menggunakan Shortest Job First (SJF). | Sesuai Harapan | **PASS** |
| **TC-ALG-02** | Memasukkan pesanan yang memiliki nilai slack waktu terburuk lebih kecil daripada *safety buffer* (dihitung jika pesanan diletakkan di akhir antrean) | Kriteria Uji Tier **Urgent** | Sistem mengklasifikasikan pesanan ke dalam Tier Urgent. Urutan penempatan dijadwalkan tepat setelah kelompok Quick-Insert selesai, diurutkan berbasis Earliest Deadline First (EDF) untuk mencegah keterlambatan. | Sesuai Harapan | **PASS** |
| **TC-ALG-03** | Memasukkan pesanan dengan nilai slack waktu yang aman (slack lebih besar dari toleransi buffer) | Kriteria Uji Tier **Normal** | Sistem mengklasifikasikan pesanan ke dalam Tier Normal. Urutan penempatan dijadwalkan pada posisi paling akhir (setelah Urgent selesai) dan diurutkan menggunakan perpaduan EDF + SJF. | Sesuai Harapan | **PASS** |
| **TC-ALG-04** | Terdapat dua pesanan pada kategori yang sama (misalnya sesama Urgent) yang memiliki tanggal deadline yang sama persis | Penanganan Bentrok Urutan (EDF Seri) | Algoritma menggunakan Shortest Job First (SJF) sebagai kriteria penentu keputusan sekunder. Pesanan dengan estimasi durasi pengerjaan yang lebih pendek ditempatkan pada posisi antrean yang lebih awal. | Sesuai Harapan | **PASS** |

---

### MODUL 6: MANAJEMEN PELANGGAN
Fokus pengujian adalah memvalidasi fungsionalitas perekaman data pelanggan.

| ID Kasus Uji | Skenario Pengujian | Kelas Input (EP/BVA) | Hasil yang Diharapkan | Hasil Aktual | Status |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **TC-CUSTM-01**| Membuka menu "Customers" di panel dashboard admin | Pengambilan Data | Sistem memanggil data dari tabel `customers` dan menampilkannya dalam tabel daftar pelanggan, lengkap dengan nama, nomor telepon, dan catatan tambahan. | Sesuai Harapan | **PASS** |

---

### MODUL 7: PENGATURAN SISTEM & PARAMETER ALGORITMA
Fokus pengujian adalah memvalidasi kelenturan konfigurasi parameter algoritma penjadwalan dan efeknya setelah disimpan.

| ID Kasus Uji | Skenario Pengujian | Kelas Input (EP/BVA) | Hasil yang Diharapkan | Hasil Aktual | Status |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **TC-SET-01** | Mengubah nilai parameter *Slack Buffer* (misal: dari `0.25` menjadi `0.40`) dan menekan "Save Settings" | Perubahan Parameter | Pengaturan baru disimpan ke tabel `settings`. Sistem memicu regenerasi jadwal produksi secara instan. Pesanan yang awalnya berada di tier Normal berpindah ke tier Urgent karena syarat kelayakan buffer menjadi lebih ketat. | Sesuai Harapan | **PASS** |
| **TC-SET-02** | Mengubah *Quick-Insert Threshold* (misal: dari `480` menjadi `240` menit) lalu menekan tombol simpan | Perubahan Parameter | Sistem memperbarui DB. Setelah regenerasi otomatis, pesanan dengan durasi di atas 240 menit yang sebelumnya masuk ke tier Quick-Insert langsung didegradasi ke tier Normal atau Urgent karena sudah melampaui ambang batas. | Sesuai Harapan | **PASS** |

---

## 4. Kesimpulan Pengujian

Berdasarkan hasil pengujian fungsionalitas sistem menggunakan metode *Blackbox Testing* dengan teknik *Equivalence Partitioning* dan *Boundary Value Analysis*, diperoleh kesimpulan sebagai berikut:

1. **Persentase Keberhasilan Pengujian**:
   $$\text{Persentase Keberhasilan} = \frac{\text{Jumlah Kasus Uji Berhasil (PASS)}}{\text{Total Kasus Uji}} \times 100\%$$
   $$\text{Persentase Keberhasilan} = \frac{31}{31} \times 100\% = 100\%$$

2. **Kesimpulan Kelayakan**:
   Seluruh kasus uji fungsional yang dirancang untuk menguji modul Autentikasi, Pemesanan Mandiri Pelanggan, Manajemen Pesanan Admin, Transisi Status Produksi, Algoritma Penjadwalan Multi-Tier, Manajemen Pelanggan, dan Pengaturan Sistem telah berhasil diselesaikan dengan status **PASS** (Berhasil). Sistem terbukti bebas dari kesalahan fungsional kritis (*critical functional bugs*) dan siap digunakan untuk lingkungan produksi maupun bahan demonstrasi skripsi.
