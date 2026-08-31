# MVP6 — SmartVoting: Simplifikasi Auth, Smart Dashboard & UX Optimization

**Versi:** 6.0  
**Tanggal:** 31 Agustus 2026  
**Status:** Perencanaan / Backlog  

---

## 📋 Ringkasan Eksekutif

MVP6 berfokus pada penyederhanaan alur pendaftaran & autentikasi (Google OAuth SSO 1-Click), perombakan dashboard admin menjadi *smart dashboard* adaptif berdasarkan kondisi Hari-H & status aktif, panduan *onboarding* interaktif bagi institusi baru, penyempurnaan alur pembuatan event ke paslon, integrasi tombol bilik di admin detail, native canvas image cropper tanpa Croppie, serta perampingan UI landing page.

---

## 🎯 Daftar Pekerjaan (Task List) — Diurutkan dari Prioritas Tinggi ke Rendah

---

### 🔴 PRIORITAS TINGGI (HIGH PRIORITY)

#### 🛠️ T-01 — Integrasi Google OAuth 1-Click Login & Auto-Provisioning Akun/Institusi
* **Prioritas:** ⭐⭐⭐ Tinggi
* **Deskripsi:**
  Menyederhanakan alur registrasi & login panjang menjadi **satu tombol "Masuk dengan Google"**. Jika pengguna baru pertama kali login dengan Google, sistem otomatis membuat akun pengguna dan profil institusi default, lalu langsung mengarahkan pengguna ke Dashboard Admin dengan status aktif/trial.
* **Cakupan Teknis:**
  - Install / konfigurasi `laravel/socialite` untuk provider Google (`client_id`, `client_secret`, `redirect_uri`).
  - Tambahkan route `GET /auth/google` (redirect) dan `GET /auth/google/callback` di `routes/web.php`.
  - Pada `AuthController` / `UserUsecase`, implementasikan *find-or-create*:
    - Cari user berdasarkan `email` atau `google_id`.
    - Jika belum ada, buat `institutions` baru (nama dari profil Google / default) dan record `users` baru dengan `access_type = 1` (Admin).
    - Lakukan `Auth::login($user, remember: true)`.
  - Sederhanakan halaman `/login` & `/subscribe` dengan menonjolkan tombol Google Sign-In.
* **File Terkait:**
  - `composer.json` (`laravel/socialite`)
  - `config/services.php`
  - `app/Http/Controllers/AuthController.php`
  - `app/Usecase/UserUsecase.php`
  - `resources/views/landing/login.blade.php` / `resources/views/landing/subscribe.blade.php`

---

#### 🛠️ T-02 — Smart Dashboard Adaptif: Kondisi Hari-H Pemilihan vs Pra-Pemilihan
* **Prioritas:** ⭐⭐⭐ Tinggi
* **Deskripsi:**
  Mengubah tampilan Dashboard Admin (`_admin/dashboard.blade.php`) agar otomatis menyesuaikan tampilan berdasarkan 2 kondisi pemilihan:
  1. **Kondisi Hari-H & Status Aktif (`today == election_date && status == 'active'`):**
     * Menampilkan **Live Monitor Mode**: Quick stats suara real-time, grafik perolehan paslon live, indikator bilik suara aktif, dan tombol darurat/kendali cepat.
     * Tombol cepat **"Buka Bilik Suara"** dan **"Cetak Berita Acara"**.
  2. **Kondisi Pra-Pemilihan / Draft / Selesai (`today < election_date || status != 'active'`):**
     * Menampilkan **Preparation Mode**: Countdown hari/jam menuju waktu pemilihan, status kelengkapan data paslon, status DPT/kuota pemilih, tombol aktivasi event, dan simulasi bilik.
* **Cakupan Teknis:**
  - Update `AdminDashboardController@index` & `AdminDashboardController@data` untuk menghitung status `is_today_event` dan `readiness_percentage`.
  - Buat partial Blade terpisah: `_admin.dashboard._live_event` dan `_admin.dashboard._preparation_event`.
* **File Terkait:**
  - `app/Http/Controllers/Admin/DashboardController.php`
  - `app/Usecase/LivePollingUsecase.php`
  - `resources/views/_admin/dashboard.blade.php`
  - `resources/views/_admin/dashboard/_live_event.blade.php` (baru)
  - `resources/views/_admin/dashboard/_preparation_event.blade.php` (baru)

---

#### 🛠️ T-03 — Onboarding Checklist Wizard untuk Pengguna / Institusi Baru di Dashboard
* **Prioritas:** ⭐⭐⭐ Tinggi
* **Deskripsi:**
  Ketika pengguna baru pertama kali masuk ke dashboard dan belum memiliki event pemilihan (atau event masih kosong), tampilkan kartu onboarding interaktif yang memandu langkah awal penggunaan aplikasi secara visual.
* **Langkah Onboarding:**
  1. **Langkah 1:** Lengkapi Profil Institusi & Logo Sekolah (Status: Selesai / Belum).
  2. **Langkah 2:** Buat Event Pemilihan Pertama (Status: Selesai / Belum).
  3. **Langkah 3:** Daftarkan Pasangan Calon (Paslon) (Status: Selesai / Belum).
  4. **Langkah 4:** Uji Coba Buka Bilik Suara / Simulasi Voting.
* **Cakupan Teknis:**
  - Di `AdminDashboardController`, periksa apakah `$electionsCount == 0` atau belum ada paslon.
  - Tampilkan banner/card progress onboarding interaktif dengan link langsung ke setiap tahapan.
  - Pengguna dapat menutup (*dismiss*) onboarding setelah semua tahapan selesai.
* **File Terkait:**
  - `app/Http/Controllers/Admin/DashboardController.php`
  - `resources/views/_admin/dashboard.blade.php`
  - `resources/views/_admin/dashboard/_onboarding_wizard.blade.php` (baru)

---

### 🟡 PRIORITAS SEDANG (MEDIUM PRIORITY)

#### 🛠️ T-04 — Alur Seamless: Otomatis Redirect ke Tambah Paslon Setelah Tambah Event
* **Prioritas:** ⭐⭐ Sedang
* **Deskripsi:**
  Saat ini, setelah admin menyimpan form Tambah Event Pemilihan, admin diarahkan kembali ke halaman index tabel. Diubah agar setelah submit event baru, admin langsung diarahkan ke halaman **Tambah Paslon** dengan `election_id` otomatis terpilih, sehingga proses setup pemilihan terasa mengalir tanpa terputus.
* **Cakupan Teknis:**
  - Update `ElectionController@doCreate`:
    ```php
    return redirect()->route('admin.candidates.add', ['election_id' => $newEventId])
        ->with('success', 'Event pemilihan berhasil dibuat! Silakan daftarkan Paslon pertama.');
    ```
  - Di form `_admin/candidates/add.blade.php`, pastikan select `election_id` otomatis ter-select jika terdapat query parameter `?election_id=...`.
* **File Terkait:**
  - `app/Http/Controllers/Admin/ElectionController.php`
  - `app/Usecase/ElectionUsecase.php`
  - `resources/views/_admin/candidates/add.blade.php`

---

#### 🛠️ T-05 — Tombol "Buka Bilik Suara" di Halaman Detail Pemilihan Admin
* **Prioritas:** ⭐⭐ Sedang
* **Deskripsi:**
  Menambahkan tombol aksi **"Buka Bilik Suara"** pada header kartu halaman detail pemilihan admin (`_admin/elections/detail.blade.php`) berdampingan dengan tombol *Buka Landing Slug* dan *Cetak Laporan*. Tombol ini langsung membuka Layar Standby Bilik Suara (`/bilik/start/{electionId}`) di tab baru.
* **Cakupan Teknis:**
  - Update view `_admin/elections/detail.blade.php` pada section Quick Actions header:
    ```blade
    <x-admin.button href="{{ route('kiosk.start', $election->id) }}" target="_blank" color="primary" size="sm">
        <svg class="size-3.5" ...></svg>
        <span>Buka Bilik Suara</span>
    </x-admin.button>
    ```
* **File Terkait:**
  - `resources/views/_admin/elections/detail.blade.php`

---

#### 🛠️ T-06 — Image Cropper Manual Native (Vanilla JS + HTML5 Canvas) Tanpa Library Croppie
* **Prioritas:** ⭐⭐ Sedang
* **Deskripsi:**
  Mengganti library eksternal `Croppie.js` dengan komponen **Native Canvas Cropper** kustom yang murni menggunakan JavaScript standar dan HTML5 Canvas API (Drag, Pinch/Scroll Zoom, Grid Box, & Export to Blob/Base64). Lebih ringan, tanpa dependensi vendor eksternal, dan responsif di perangkat mobile/tablet.
* **Cakupan Teknis:**
  - Hapus import Croppie CDN di layout / komponen.
  - Tulis modul JavaScript native `canvas-cropper.js` untuk memotong foto paslon/logo (rasio 1:1 dan 4:3) dengan preview interaktif.
  - Perbarui blade component `resources/views/components/admin/image-cropper.blade.php`.
* **File Terkait:**
  - `resources/views/components/admin/image-cropper.blade.php`
  - `resources/js/admin-custom.js` / `resources/js/canvas-cropper.js`
  - `resources/css/admin-custom.css`

---

### 🟢 PRIORITAS RENDAH (LOW PRIORITY - LANDING PAGE STREAMLINING)

#### 🛠️ T-07 — Sederhanakan Navbar Landing Page (1 Tombol Utama Masuk Aplikasi)
* **Prioritas:** ⭐ Rendah
* **Deskripsi:**
  Menghilangkan tombol ganda yang membingungkan (*Masuk Akun* vs *Daftar / Berlangganan*), digantikan dengan **1 tombol utama** yang jelas: **"Masuk ke Aplikasi"** / **"Coba Gratis"** (mengarahkan langsung ke alur Google Auth / Login).
* **Cakupan Teknis:**
  - Update `resources/views/landing/partials/header.blade.php`: Satukan tombol desktop & mobile menjadi satu CTA button tunggal.
* **File Terkait:**
  - `resources/views/landing/partials/header.blade.php`

---

#### 🛠️ T-08 — Perampingan Hero Banner (Hapus Tombol Redundant)
* **Prioritas:** ⭐ Rendah
* **Deskripsi:**
  Di section Banner Hero (`landing/partials/hero.blade.php`), hapus tombol sekunder yang redundan dan fokuskan pada 1 tombol aksi utama: **"Coba Aplikasi Sekarang"** (dengan ikon Google / direct link) dan link sekunder ringkas ke dokumentasi atau video demo.
* **Cakupan Teknis:**
  - Update `resources/views/landing/partials/hero.blade.php`: rapikan layout CTA hero agar tidak menumpuk 2 tombol besar yang serupa fungsinya.
* **File Terkait:**
  - `resources/views/landing/partials/hero.blade.php`

---

#### 🛠️ T-09 — Sembunyikan Bagian Paket Harga & Ganti dengan CTA "Coba Aplikasi Sekarang"
* **Prioritas:** ⭐ Rendah
* **Deskripsi:**
  Sembunyikan pilihan paket harga yang kaku pada landing page (`landing/partials/pricing.blade.php`) dan ganti dengan kartu tawaran akses instan / trial gratis dengan tombol **"Coba Aplikasi (Login dengan Google)"**.
* **Cakupan Teknis:**
  - Perbarui `resources/views/landing/partials/pricing.blade.php` atau ubah menjadi section "Mulai Gunakan SmartVoting Hari Ini".
  - Hapus navigasi link "Paket Harga" di header dan footer, arahkan semuanya ke alur coba aplikasi instan.
* **File Terkait:**
  - `resources/views/landing/partials/pricing.blade.php`
  - `resources/views/landing/partials/header.blade.php`
  - `resources/views/landing/partials/footer.blade.php`

---

## 📊 Matriks Estimasi & Dependensi

| ID | Task | Prioritas | Kompleksitas | Dependensi |
|---|---|---|---|---|
| **T-01** | Google OAuth 1-Click Login & Auto-Provisioning | **Tinggi** | Sedang | `laravel/socialite` |
| **T-02** | Smart Dashboard Adaptif (Hari-H vs Pra-Pemilihan) | **Tinggi** | Sedang | - |
| **T-03** | Onboarding Setup Wizard di Dashboard Admin | **Tinggi** | Rendah | T-02 |
| **T-04** | Auto-Redirect ke Tambah Paslon setelah Buat Event | **Sedang** | Rendah | - |
| **T-05** | Tombol "Buka Bilik" di Detail Pemilihan Admin | **Sedang** | Rendah | - |
| **T-06** | Native HTML5 Canvas Image Cropper (No Croppie) | **Sedang** | Sedang | - |
| **T-07** | Sederhanakan Navbar Landing Page (1 Tombol) | **Rendah** | Rendah | T-01 |
| **T-08** | Perampingan CTA Hero Banner | **Rendah** | Rendah | T-01 |
| **T-09** | Sembunyikan Pricing -> CTA Coba Aplikasi | **Rendah** | Rendah | T-01 |

---
