# SmartVoting — Style & Design Reference

**Theme:** Light & Dark Mode (Auto-adapt)
**UI Framework:** Tailwind CSS v4 + Preline UI

SmartVoting menggunakan desain antarmuka bergaya *dashboard* administratif modern yang bersih, fungsional, dan responsif. Sistem desain ini mengandalkan komponen dari **Preline UI** dengan tipografi yang jernih dari font **Geist**, memberikan nuansa profesional, tegas, dan mudah dibaca (khususnya penting untuk lingkungan TPS / bilik suara). Antarmuka mendukung transisi mulus antara mode terang (*Light Mode*) dan gelap (*Dark Mode*).

---

## Tokens — Colors

Palet warna dibangun di atas warna semantik (*Semantic Colors*) Tailwind CSS bawaan. Warna akan otomatis beradaptasi saat pengguna berpindah antara mode Terang dan Gelap.

| Name | Role | Light Mode Value | Dark Mode Value |
|------|------|-------------------|------------------|
| **Primary** | Aksi utama, tombol CTA utama, tautan, indikator aktif | `blue-600` | `blue-500` |
| **Secondary** | Teks sekunder, label, ikon pendukung, border | `slate-600` | `slate-400` |
| **Success** | Indikator sukses, status "Active", notifikasi berhasil | `emerald-600` | `emerald-500` |
| **Danger** | Aksi destruktif, peringatan error, status "Closed" | `red-600` | `red-500` |
| **Warning** | Peringatan, status "Scheduled/Draft" | `amber-500` | `amber-400` |
| **Info** | Informasi tambahan, *badge* informatif | `cyan-600` | `cyan-500` |

### Surfaces (Latar Belakang & Elemen)

- **Background Utama (Light):** `bg-gray-50`
- **Background Utama (Dark):** `dark:bg-neutral-900`
- **Permukaan Kartu/Card (Light):** `bg-white` dengan `border-gray-200`
- **Permukaan Kartu/Card (Dark):** `dark:bg-neutral-800` (atau 900) dengan `dark:border-neutral-700`

---

## Tokens — Typography

Aplikasi ini menggunakan jenis huruf **Geist** sebagai *sans-serif* utama untuk seluruh antarmuka.

```css
--font-sans: 'Geist', ui-sans-serif, system-ui, sans-serif;
```

**Penggunaan Bobot (Weights):**
- **Regular (400):** Teks paragraf, tabel data, label formulir.
- **Medium (500):** Teks tombol, *badge*, item navigasi sidebar.
- **Semibold (600):** Sub-judul, header tabel, penekanan angka di kartu metrik.
- **Bold (700):** Judul halaman (*Page Title*), Judul Event, Nama Paslon.

**Hierarki Ukuran:**
- **Micro (`text-xs`):** 12px — *Badge*, *helper text*, *timestamp*.
- **Small (`text-sm`):** 14px — Konten tabel, menu navigasi, label *input*.
- **Base (`text-base`):** 16px — Teks paragraf utama, tombol besar.
- **Heading 3 (`text-lg` - `text-xl`):** 18px-20px — Judul kartu, sub-bagian.
- **Heading 2 (`text-2xl`):** 24px — Judul halaman utama.
- **Display Kiosk (`text-3xl` - `text-5xl`):** Digunakan khusus di layar Kiosk bilik suara agar nama paslon terlihat jelas dari jarak pandang berdiri.

---

## Tokens — Spacing & Shapes

**Border Radius:**
SmartVoting menggunakan sudut yang membulat lembut (*soft rounded*) untuk memberikan kesan modern namun tetap formal.
- **Input & Tombol Kecil:** `rounded-lg` (8px)
- **Kartu Standar & Modal:** `rounded-xl` (12px)
- **Kartu Metrik & Kiosk:** `rounded-3xl` (24px) - *Khusus pada layar bilik suara dan dasbor untuk memberi kesan lapang.*

**Shadows (Elevasi):**
Elevasi sangat dijaga agar antarmuka tetap terasa *flat* dan bersih.
- **Kartu Dasar:** `shadow-sm`
- **Dropdown & Modal:** `shadow-lg` atau `shadow-xl`

**Spacing (Padding/Margin):**
- Kontainer utama memiliki padding `p-4` hingga `p-6`.
- Jarak antar elemen menggunakan gap `gap-4` hingga `gap-6`.

---

## Layout & Components

### 1. Admin Layout
Menggunakan pola *Sidebar Navigation* tradisional:
- **Sidebar Kiri:** Kontainer navigasi yang bisa ditarik/ditutup (*collapsible* pada mobile).
- **Header Atas:** Berisi *breadcrumb*, pencarian (opsional), dan menu *Profile/Theme Toggle*.
- **Area Konten:** Latar belakang abu-abu terang (`bg-gray-50`) dengan kartu-kartu konten berwarna putih (`bg-white`) yang memiliki shadow tipis (`shadow-sm`).

### 2. Operator & Kiosk Layout
- **Operator Menu:** Menyerupai *grid layout* kartu. Fokus pada tombol "Buka Bilik Suara" yang mencolok.
- **Layar Kiosk (Bilik Suara):** Bebas dari distraksi (tanpa *sidebar*, tanpa *header* navigasi). Hanya menampilkan daftar paslon dalam bentuk *Grid Card* besar di tengah layar dengan penekanan pada foto dan nama, serta tombol "PILIH" berukuran ekstra besar.

### 3. Komponen Spesifik
- **Tabel Data:** Mengikuti gaya tabel Preline UI dengan garis pemisah baris tipis (`divide-y divide-gray-200 dark:divide-neutral-700`), warna latar kepala tabel terang (`bg-gray-50 dark:bg-neutral-800`).
- **Badge Status:** `inline-flex` dengan latar belakang transparan/pudar (`bg-emerald-100 text-emerald-800` untuk aktif).
- **Formulir:** Input field menggunakan border standar, radius `rounded-lg`, dan perubahan warna tepi (ring) biru (*primary*) saat status *focus*.

---

## Do's and Don'ts

### Do
- Gunakan warna semantik Tailwind (Blue, Emerald, Red, dll) sesuai konteksnya.
- Selalu sediakan varian `dark:` untuk setiap warna latar belakang, teks, dan *border*.
- Gunakan komponen bentukan *Preline UI* untuk konsistensi struktur (dropdown, modal, tabel).
- Buat tampilan layar bilik suara (Kiosk) sebesar dan sebersih mungkin (minimalkan teks, besarkan area sentuh/klik).

### Don't
- Hindari penggunaan warna *custom hexadecimal* kecuali tidak ada padanan di sistem warna Tailwind.
- Jangan campurkan font lain selain **Geist**.
- Jangan gunakan bayangan (*drop-shadow*) berlebih pada elemen formulir atau tabel; gunakan hanya untuk melayang (*floating*) seperti *dropdown* atau *modal*.

---

## Quick Reference (Tailwind CSS v4 Configuration)

Struktur warna dan font utama didefinisikan langsung melalui `app.css` menggunakan fitur tema Tailwind v4:

```css
@theme {
    --font-sans: 'Geist', ui-sans-serif, system-ui, sans-serif;

    /* Semantic Colors dipetakan dari variabel CSS dinamis */
    --color-primary: var(--semantic-primary);
    --color-secondary: var(--semantic-secondary);
    --color-success: var(--semantic-success);
    --color-danger: var(--semantic-danger);
    --color-warning: var(--semantic-warning);
    --color-info: var(--semantic-info);
}

@layer base {
    :root {
        --semantic-primary: var(--color-blue-600);
        --semantic-success: var(--color-emerald-600);
        /* dst... */
    }

    .dark {
        --semantic-primary: var(--color-blue-500);
        --semantic-success: var(--color-emerald-500);
        /* dst... */
    }
}
```
