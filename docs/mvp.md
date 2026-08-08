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

## Out of Scope (Next Phase)
- Upload/Kelola daftar pemilih (Voters).
- Layar TPS Operator & Bilik Suara Kiosk.
- Hitung Suara/Laporan Hasil.
