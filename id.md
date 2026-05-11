# Alur Penyusunan Jadwal Produksi

Flowchart ini menjelaskan tahapan logika yang digunakan oleh algoritma penjadwalan pada `M_schedule::generate()`.

```mermaid
flowchart TD
    %% Styling
    classDef startEnd fill:#2d3748,stroke:#4a5568,color:#fff,stroke-width:2px;
    classDef process fill:#ebf8ff,stroke:#3182ce,color:#2b6cb0,stroke-width:2px;
    classDef decision fill:#faf5ff,stroke:#805ad5,color:#553c9a,stroke-width:2px;
    classDef db fill:#f0fff4,stroke:#38a169,color:#276749,stroke-width:2px;
    classDef tier fill:#fffaf0,stroke:#dd6b20,color:#c05621,stroke-width:2px;

    Start([Proses Dimulai]):::startEnd --> FetchDB[(Mengambil order yang dapat dijadwalkan<br/>& job yang masih berjalan)]:::db
    FetchDB --> EmptyCheck{Apakah terdapat order<br/>yang perlu dijadwalkan?}:::decision

    EmptyCheck -- Tidak --> End([Proses Selesai]):::startEnd
    EmptyCheck -- Ya --> Anchor[Menentukan anchor time<br/>yaitu waktu selesai job aktif terakhir atau waktu saat ini,<br/>kemudian disesuaikan dengan jam operasional]:::process

    Anchor --> LoopStart{Memeriksa setiap order<br/>secara berurutan}:::decision

    %% Tier 1: Quick Insert
    LoopStart --> QICheck{Quick-Insert?<br/>Ukuran kecil, mendekati deadline,<br/>dan masih dapat dijadwalkan pada hari yang sama}:::decision
    QICheck -- Ya --> QITier[Memasukkan order ke Tier Quick-Insert]:::tier

    %% Tier 2 & 3: Slack Analysis
    QICheck -- Tidak --> SlackCalc[Menghitung slack terburuk<br/>dengan asumsi job ditempatkan pada urutan paling akhir]:::process
    SlackCalc --> SlackCheck{Apakah slack<br/>lebih kecil dari safety buffer?}:::decision

    SlackCheck -- Ya --> UTier[Memasukkan order ke Tier Urgent]:::tier
    SlackCheck -- Tidak --> NTier[Memasukkan order ke Tier Normal]:::tier

    %% Re-loop
    QITier --> LoopStart
    UTier --> LoopStart
    NTier --> LoopStart

    %% Sorting Phase
    LoopStart -- Seluruh order telah diklasifikasikan --> SortPhase[Proses Pengurutan]:::process

    SortPhase --> SortQI[Pengurutan Quick-Insert:<br/>SJF, yaitu durasi terpendek diprioritaskan]:::process
    SortPhase --> SortU[Pengurutan Urgent:<br/>EDF diprioritaskan, kemudian SJF digunakan apabila deadline sama]:::process
    SortPhase --> SortN[Pengurutan Normal:<br/>EDF diprioritaskan, kemudian SJF digunakan apabila deadline sama]:::process

    %% Database Operations
    SortQI --> ClearDB[(Menghapus jadwal lama<br/>yang belum dimulai)]:::db
    SortU --> ClearDB
    SortN --> ClearDB

    ClearDB --> InsertQI[(Menyimpan Quick-Insert<br/>mulai dari waktu saat ini)]:::db
    InsertQI --> InsertU[(Menyimpan Urgent<br/>mulai dari anchor time)]:::db
    InsertU --> InsertN[(Menyimpan Normal<br/>setelah proses Urgent selesai)]:::db

    InsertN --> Commit[(Commit Transaction)]:::db
    Commit --> End
```

### Penjelasan Singkat

1. **Anchor Time**  
   Anchor time digunakan untuk menjaga kesinambungan proses produksi. Apabila masih terdapat job yang sedang berjalan, maka penyusunan jadwal baru mengikuti perkiraan waktu selesai job tersebut.

2. **Quick-Insert**  
   Order dimasukkan ke kategori ini apabila ukuran pekerjaan relatif kecil, memiliki deadline yang dekat, dan masih tersedia slot waktu pada hari yang sama.

3. **Analisis Slack Terburuk**  
   Sistem menghitung kondisi slack dengan asumsi bahwa job ditempatkan pada urutan paling akhir. Apabila nilai slack lebih kecil dari safety buffer, order tersebut dikategorikan sebagai **Urgent**. Jika masih aman, order dimasukkan ke **Normal**.

4. **Sorting EDF dan SJF**  
   Setelah klasifikasi selesai, setiap kategori diurutkan kembali. Metode **EDF (Earliest Deadline First)** digunakan untuk memprioritaskan deadline terdekat, sedangkan **SJF (Shortest Job First)** digunakan sebagai urutan tambahan apabila terdapat deadline yang sama.

### Inti Logika
- Order dengan ukuran kecil dan tingkat urgensi tinggi dapat langsung dimasukkan ke kategori **Quick-Insert**.
- Order dengan risiko keterlambatan yang tinggi masuk ke kategori **Urgent**.
- Order yang masih memiliki tingkat keamanan waktu yang baik masuk ke kategori **Normal**.
- Seluruh order kemudian diurutkan kembali sebelum disimpan ke database.
