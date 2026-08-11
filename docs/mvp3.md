# MVP3 — SmartVoting: Revisi & Fitur Tambahan

**Versi:** 3.0
**Tanggal:** 11 Agustus 2026
**Status:** Perencanaan

---

## Latar Belakang

MVP3 berfokus pada penyempurnaan pengalaman pengguna (UX), perbaikan bug/alur, penambahan fitur informasi, dan penambahan landing page publik per event pemilihan.

---

## Daftar Pekerjaan (Task List)

### 🛠️ T-01 — Tampilkan Detail Paslon Saat Operator Buka Bilik Suara
**Prioritas:** Tinggi

**Deskripsi:**
Saat Operator menekan tombol **"Buka Bilik Suara"**, tampilkan ringkasan daftar paslon yang terdaftar pada event tersebut (nomor urut, nama ketua, nama wakil) di halaman/modal konfirmasi, sebelum token sesi dibuatkan.

**Cakupan:**
- Modifikasi halaman `_operator/kiosk/index.blade.php` — tambahkan panel/card ekspandabel yang menampilkan daftar paslon saat event diklik/hover.
- Atau tambahkan modal konfirmasi sebelum POST ke `operator.kiosk.generate`, berisi tabel paslon yang di-fetch via AJAX dari endpoint baru.
- Tambahkan method `getCandidatesByElection(int $electionId): array` di `LivePollingUsecase`.
- Tambahkan route & controller method baru: `GET /operator/kiosk/{electionId}/candidates` → `KioskManagerController@candidates` (return JSON).

**File yang Diubah:**
- `resources/views/_operator/kiosk/index.blade.php`
- `app/Http/Controllers/Operator/KioskManagerController.php`
- `app/Usecase/LivePollingUsecase.php`
- `routes/web.php`

---

### 🛠️ T-02 — Redirect Otomatis dari Halaman "Terima Kasih" ke Idle/Bilik
**Prioritas:** Tinggi

**Deskripsi:**
Saat ini, jika pemilih menekan F5/refresh pada halaman setelah submit suara (halaman "Terima Kasih"), tampilan rusak. Halaman tersebut harus langsung redirect ke halaman idle kiosk (`/bilik/start/{electionId}`).

**Cakupan:**
- Identifikasi view yang dirender setelah sukses submit (kemungkinan view `kiosk/vote.blade.php` dengan bagian terima kasih, atau view terpisah).
- Tambahkan meta refresh atau redirect berbasis JavaScript saat halaman dimuat ulang (cek session status).
- Alternatif: Jika `voting_sessions.status = submitted` atau `expired` dan user akses ulang URL token, redirect langsung ke `/bilik/start/{electionId}` alih-alih menampilkan error.
- Review logika di `KioskController@vote` untuk memastikan token yang sudah submitted/expired → redirect (bukan error).

**File yang Diubah:**
- `app/Http/Controllers/KioskController.php`
- `resources/views/kiosk/vote.blade.php` (bagian layar sukses)

---

### 🛠️ T-03 — Hapus Tampilan Loading/Countdown 15 Detik di "Terima Kasih"
**Prioritas:** Sedang

**Deskripsi:**
Tampilan countdown 15 detik di halaman konfirmasi setelah memilih ("Terima Kasih") tidak diperlukan dan membingungkan pemilih. Hapus UI tersebut.

**Cakupan:**
- Cari dan hapus blok timer countdown di `resources/views/kiosk/vote.blade.php` (seksi layar sukses).
- Pastikan layar setelah submit langsung statis/bersih tanpa animasi hitung mundur.
- Redirect tetap bisa dilakukan, tapi tanpa menampilkan timer.

**File yang Diubah:**
- `resources/views/kiosk/vote.blade.php`

---

### 🛠️ T-04 — Tambahkan Auto-Redirect di Halaman "Akses Ditolak"
**Prioritas:** Sedang

**Deskripsi:**
Halaman `kiosk/error.blade.php` (Akses Ditolak) saat ini statis. Tambahkan timeout otomatis (misal 10 detik) yang akan redirect pemilih kembali ke halaman idle bilik, agar layar kiosk tidak terjebak di halaman error.

**Cakupan:**
- Tambahkan variabel `$electionId` ke view `error.blade.php` (kirim dari controller saat ada error).
- Tambahkan countdown countdown JavaScript di view `error.blade.php`.
- Setelah hitung mundur selesai, lakukan `window.location.href = '/bilik/start/{electionId}'`.
- Jika `$electionId` tidak tersedia (kasus langka), redirect ke halaman login saja.

**File yang Diubah:**
- `resources/views/kiosk/error.blade.php`
- `app/Http/Controllers/KioskController.php` (pastikan kirim `$electionId` ke view error)

---

### 🛠️ T-05 — Sembunyikan Menu "Manajemen Sidebar" dari Navigasi Admin
**Prioritas:** Rendah

**Deskripsi:**
Menu **Manajemen Sidebar** adalah menu teknis yang tidak perlu terlihat oleh pengguna sehari-hari. Sembunyikan dari tampilan navigasi admin (tapi jangan dihapus, karena masih digunakan di backend).

**Cakupan:**
- Hapus atau nonaktifkan entri menu `admin.sidebar_menu.index` dari konfigurasi menu sidebar yang ditampilkan.
- Cek bagaimana menu sidebar di-generate (kemungkinan dari database tabel `sidebar_menu` atau hardcode di view layout).
- Jika dari database: ubah record `sidebar_menu` yang terkait sidebar_menu agar tidak muncul (misal kolom `is_visible = false`).
- Jika dari konfigurasi: filter/exclude `admin.sidebar_menu.*` dari daftar menu yang di-render.

**File yang Diubah:**
- Tergantung pada implementasi menu (bisa migration, atau view layout `_admin/_layout/app.blade.php`)

---

### 🛠️ T-06 — Halaman Detail Election & Ubah Tombol "Cetak" Menjadi "Detail Laporan"
**Prioritas:** Tinggi

**Deskripsi:**
Saat ini tombol "Cetak Laporan" di dashboard langsung membuka halaman cetak. Ubah alur ini menjadi:
1. Dashboard → Tombol **"Detail Laporan"** → Halaman Detail Election (`admin/elections/{id}/detail`).
2. Halaman Detail Election menampilkan: info event, daftar paslon + perolehan suara, grafik, dan dari sini ada tombol **"Cetak/Print"**.
3. Pada halaman Daftar Pemilihan (`admin/elections`), juga tambahkan tombol **"Lihat Detail"** yang mengarah ke halaman yang sama.

**Cakupan:**
- Buat view baru: `resources/views/_admin/elections/detail.blade.php`.
- Buat method `detail(int $id): View` di `ElectionController`.
- Pindahkan logika query dari `DashboardController@print` ke `LivePollingUsecase@getLiveResults` (sudah ada), gunakan ulang dari `ElectionController@detail`.
- Tambahkan route: `GET /admin/elections/{id}/detail` → `admin.elections.detail`.
- Ubah tombol di `dashboard.blade.php`: label ganti dari "Cetak Laporan" menjadi "Detail Laporan", URL ganti ke `admin.elections.detail`.
- Tambahkan tombol "Lihat Detail" di `elections/index.blade.php`.
- Halaman `detail.blade.php` berisi: grafik bar perolehan suara, tabel paslon + suara, dan tombol "Cetak".

**File yang Diubah:**
- `resources/views/_admin/elections/detail.blade.php` **(BARU)**
- `app/Http/Controllers/Admin/ElectionController.php`
- `resources/views/_admin/dashboard.blade.php`
- `resources/views/_admin/elections/index.blade.php`
- `routes/web.php`

---

### 🛠️ T-07 — Tabel Riwayat Aktivitas Voting di Dashboard
**Prioritas:** Sedang

**Deskripsi:**
Tambahkan tabel **"Riwayat Aktivitas Voting"** di Dasbor Admin yang menampilkan sesi-sesi voting terbaru, mencakup kolom: Waktu Dibuka, Waktu Ditutup, Status Sesi (`open/submitted/expired`), ID Election, dan Nama Operator yang membuka sesi.

**Cakupan:**
- Tambahkan method `getRecentSessions(int $limit = 20): array` di `LivePollingUsecase` — query `JOIN voting_sessions + users + elections`.
- Ubah `DashboardController@index` untuk memanggil method baru ini dan mengirimkan data ke view.
- Tambahkan section tabel di bagian bawah `dashboard.blade.php`.
- Kolom yang ditampilkan: No., Nama Event, Operator, Status, Waktu Dibuka, Waktu Ditutup.
- Badge berwarna pada kolom Status: `submitted` = hijau, `expired` = merah, `open` = biru.

**File yang Diubah:**
- `app/Usecase/LivePollingUsecase.php`
- `app/Http/Controllers/Admin/DashboardController.php`
- `resources/views/_admin/dashboard.blade.php`

---

### 🛠️ T-08 — Landing Page Publik Per Event Pemilihan
**Prioritas:** Tinggi

**Deskripsi:**
Setiap event pemilihan memiliki halaman publik (*landing page*) yang bisa diakses tanpa login. Halaman ini menampilkan informasi resmi event (nama, deskripsi, tanggal, status) dan daftar paslon (nomor urut, nama ketua, nama wakil).

**Cakupan:**
- Buat controller baru: `app/Http/Controllers/ElectionLandingController.php`.
- Tambahkan route publik: `GET /pemilihan/{slug}` → `ElectionLandingController@show`.
- Buat view: `resources/views/landing/election.blade.php` — desain responsif yang bisa diakses publik (tanpa sidebar admin).
- Data yang ditampilkan: nama event, deskripsi, jadwal mulai-selesai, status, daftar paslon dengan nama & nomor urut.
- Jika status event `draft`, tampilkan pesan "Pemilihan ini belum dibuka untuk publik".

**File yang Diubah:**
- `app/Http/Controllers/ElectionLandingController.php` **(BARU)**
- `resources/views/landing/election.blade.php` **(BARU)**
- `resources/views/landing/layout.blade.php` **(BARU)** — layout khusus landing page (bersih, tanpa auth nav)
- `routes/web.php`

---

### 🛠️ T-09 — Tambah Kolom `slug` pada Tabel `elections`
**Prioritas:** Tinggi (Prasyarat T-08)

**Deskripsi:**
Tambahkan kolom `slug` pada tabel `elections` untuk mendukung URL yang ramah (*human-readable*) pada landing page publik setiap event.

**Cakupan:**
- Buat migration baru: `add_slug_to_elections_table`.
- Kolom: `slug VARCHAR(255) UNIQUE NULLABLE`.
- Generate otomatis slug dari `name` saat create election (pakai `Str::slug()`).
- Tambahkan field `slug` di form tambah & edit election (`elections/add.blade.php`, `elections/update.blade.php`).
- Validasi: `unique:elections,slug,{id}` pada update.
- Update `ElectionUsecase@create` dan `ElectionUsecase@update` untuk menyimpan kolom `slug`.
- Tampilkan kolom slug di halaman `elections/index.blade.php` beserta tautan ke landing page.

**File yang Diubah:**
- `database/migrations/xxxx_add_slug_to_elections_table.php` **(BARU)**
- `app/Usecase/ElectionUsecase.php`
- `resources/views/_admin/elections/add.blade.php`
- `resources/views/_admin/elections/update.blade.php`
- `resources/views/_admin/elections/index.blade.php`

---

### 🛠️ T-10 — Lokalisasi Nama Menu ke Bahasa Indonesia
**Prioritas:** Rendah

**Deskripsi:**
Semua nama menu di sidebar admin diubah menjadi Bahasa Indonesia yang baku dan jelas.

**Cakupan:**
- Identifikasi sumber nama menu (dari DB `sidebar_menu` atau hardcode di view layout).
- Ubah nama-nama menu:
  - "Dashboard" → **"Dasbor"**
  - "Elections" → **"Pemilihan"**
  - "Candidates" → **"Paslon"**
  - "Users" → **"Pengguna"**
  - "Sidebar Menu" → *(disembunyikan — lihat T-05)*
  - "Profile" → **"Profil"**
  - "Logout" → **"Keluar"**
- Update record di database atau string di view/config sesuai sumber data menu.

**File yang Diubah:**
- Database (tabel `sidebar_menu` — via seeder atau migration)
- atau `resources/views/_admin/_layout/app.blade.php` / partial sidebar

---

## Urutan Pengerjaan yang Disarankan

```
T-09 (Slug Migration) 
  → T-08 (Landing Page)
  → T-06 (Detail Election + Tombol)
  → T-07 (Tabel Riwayat Dashboard)
  → T-01 (Detail Paslon di Operator)
  → T-02 (Redirect Terima Kasih)
  → T-03 (Hapus Countdown)
  → T-04 (Timeout Akses Ditolak)
  → T-05 (Sembunyikan Sidebar Menu)
  → T-10 (Nama Menu B. Indonesia)
```

---

## Catatan Teknis

- Semua Usecase baru/tambahan mengikuti pattern `App\Usecase` yang sudah ada (extends `Usecase`).
- Tidak ada perubahan arsitektur: tetap **Controller → Usecase → DB**.
- Slug dibuat menggunakan `Illuminate\Support\Str::slug()`.
- Landing page menggunakan layout tersendiri (bukan `_admin._layout.app`), bebas dari autentikasi.
- Route landing page **tidak** masuk dalam grup middleware `auth`.
