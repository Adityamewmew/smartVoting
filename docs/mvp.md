# MVP - SmartVoting (Phase 1)

**Fokus:** Modul Admin
**Target:** Setup data dasar (User, Event, Kandidat)

## Task 1: CRUD User (Admin & Operator)
- **Tujuan:** Admin dapat membuat akun untuk sesama Admin dan Operator.
- **Data (Tabel `users`):** Nama, Email, Password, Role (Enum: `admin`, `operator`).
- **Scope MVP:**
  - List Data User.
  - Create User.
  - Edit User (kecuali edit email jika terlalu rumit).
  - Delete User.
  - *Setup Login* (menggunakan bawaan Laravel starter kit).

## Task 2: CRUD Event (Pemilihan)
- **Tujuan:** Admin dapat membuat event pemilihan.
- **Data (Tabel `elections`):** Nama, Deskripsi, Jadwal Mulai, Jadwal Selesai, Status (`draft`, `scheduled`, `active`, `closed`).
- **Scope MVP:**
  - List Data Event.
  - Create Event.
  - Edit Event.
  - Delete Event.
  - *Skip logic auto-status dulu. Hanya CRUD dasar.*

## Task 3: CRUD Kandidat (Paslon)
- **Tujuan:** Admin dapat memasukkan paslon untuk suatu Event Pemilihan.
- **Data (Tabel `candidates`):** Election ID, Nomor Urut, Nama Ketua, Nama Wakil, Visi, Misi, Foto.
- **Scope MVP:**
  - List Data Kandidat (difilter per Event).
  - Create Kandidat.
  - Edit Kandidat.
  - Delete Kandidat.
  - Upload & Hapus Foto Paslon sederhana.

## Task 4: Modul Bilik Suara (Kiosk Mode)
- **Tujuan:** Layar khusus untuk pemilih melakukan pencoblosan. Karena pemilih absen secara manual di tempat, sistem hanya menyediakan layar pemilihan yang dijaga oleh Operator.
- **Data (Tabel `votes`):** Election ID, Candidate ID, Waktu Coblos.
- **Scope MVP:**
  - Halaman Operator untuk mengaktifkan "Sesi Bilik Suara" berdasarkan Event Pemilihan yang aktif.
  - Halaman Kiosk (Tampilan penuh/layar besar) yang menampilkan kartu Paslon.
  - Alur Pemilihan: Pilih Paslon -> Modal Konfirmasi -> Notifikasi Sukses -> Kembali ke layar Kiosk untuk pemilih selanjutnya.
  - Keamanan sederhana: Mode Kiosk mencegah pemilih keluar dari layar tanpa intervensi Operator.

## Task 5: Laporan Hasil Suara (Dashboard Admin)
- **Tujuan:** Menampilkan perolehan suara secara real-time.
- **Scope MVP:**
  - Menampilkan total suara masuk.
  - Menampilkan persentase dan jumlah suara masing-masing kandidat dalam bentuk grafik (bar/pie chart sederhana) atau angka.

## Out of Scope (Next Phase)
- Cetak Laporan PDF/Excel.
- Sistem Token/Barcode 1-time use (jika kedepannya butuh verifikasi digital).
- Hitung Suara/Laporan Hasil Real-time.
