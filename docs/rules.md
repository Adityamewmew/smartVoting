# Codebase Rules

Dokumen ini menjelaskan aturan kerja untuk starter kit ini: flow logic, struktur layer, UI, database, dan pola implementasi fitur.

## Stack

- Backend menggunakan Laravel 13 dan PHP.
- Frontend menggunakan Blade, Tailwind CSS v4, Preline UI, dan Vite.
- Interaksi halaman admin menggunakan custom SPA-like navigation berbasis jQuery.
- Database runtime menggunakan MySQL lewat Laravel Query Builder.
- Schema/migration database dikelola lewat Drizzle + Bun, bukan Laravel migration standar.
- Testing menggunakan Pest.

## Flow Aplikasi

Flow utama aplikasi:

```text
Route -> Controller -> Usecase -> Query Builder -> Response array -> Blade View
```

Aturan umum:

- Route hanya mengarahkan request ke controller.
- Controller hanya menerima request, memanggil usecase, lalu mengembalikan view atau redirect.
- Usecase berisi validasi, business logic, query database, transaksi, dan formatting response.
- View hanya bertugas menampilkan data dan form.
- Jangan menaruh query database atau business logic di Blade.

## Routing

- Route web berada di `routes/web.php`.
- Route admin menggunakan prefix `admin` dan name prefix `admin.`.
- Route admin wajib memakai middleware `auth`.
- Route yang hanya boleh diakses role tertentu wajib memakai middleware `access_type`.
- Gunakan named route untuk semua link dan redirect.

Contoh:

```php
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::middleware('access_type:1')->prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
    });
});
```

## Controller

Controller berada di `app/Http/Controllers`.

Aturan:

- Inject usecase lewat constructor property promotion.
- Deklarasikan return type secara eksplisit.
- Gunakan property `$page` untuk metadata halaman.
- Gunakan `$baseRedirect` untuk redirect fallback.
- Gunakan named arguments saat memanggil usecase.
- Gunakan `ResponseConst` untuk pesan sukses/error.
- Jangan menulis query database di controller.
- Jangan menulis business logic di controller.

Pola controller:

```php
class UserController extends Controller
{
    protected array $page = [
        'route' => 'user',
        'title' => 'Pengguna Aplikasi',
    ];

    protected string $baseRedirect;

    public function __construct(
        protected UserUsecase $usecase
    ) {
        $this->baseRedirect = 'admin/'.$this->page['route'];
    }
}
```

## Usecase

Usecase berada di `app/Usecase`.

Aturan:

- Semua usecase extend `App\Usecase\Usecase`.
- Validasi input menggunakan `Validator::make()`.
- Query database menggunakan `DB::table()`.
- Jangan pakai Repository Pattern.
- Jangan pakai Eloquent untuk query fitur utama, kecuali Laravel auth/seeder/factory membutuhkan model.
- Mutasi data wajib dibungkus transaction.
- Error wajib dicatat dengan `Log::error()`.
- Return selalu memakai `App\Http\Presenter\Response`.

Pola mutasi:

```php
DB::beginTransaction();

try {
    DB::table(DatabaseConst::USER())->insert([
        'name' => $data['name'],
        'created_by' => Auth::user()?->id,
        'created_at' => now(),
    ]);

    DB::commit();

    return Response::buildSuccessCreated();
} catch (Exception $e) {
    DB::rollback();
    Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

    return Response::buildErrorService($e->getMessage());
}
```

## Database

Aturan database:

- Nama tabel wajib diambil dari `App\Constants\DatabaseConst`.
- Jangan hardcode nama tabel langsung di usecase.
- Soft delete menggunakan kolom `deleted_at` dan `deleted_by`.
- Query list/detail wajib filter `whereNull('deleted_at')` jika tabel mendukung soft delete.
- Audit trail menggunakan `created_by`, `updated_by`, `deleted_by`.
- Timestamp diisi manual dengan `now()`.
- Gunakan alias tabel saat join.
- Data dari `first()` dikonversi dengan `collect($data)->toArray()`.

Schema database:

- Definisi schema ada di `db-migrator-with-drizzle/src/db/schema.ts`.
- Migration SQL hasil Drizzle ada di `db-migrator-with-drizzle/drizzle`.
- Seeder Laravel ada di `database/seeders`.

## Response

Usecase harus mengembalikan array standar lewat `App\Http\Presenter\Response`.

Format sukses:

```php
Response::buildSuccess(data: $data);
Response::buildSuccessCreated();
```

Format error:

```php
Response::buildErrorService($message);
Response::buildErrorNotFound($message);
Response::buildError($code, $message, $data);
```

Controller membaca response dengan pola:

```php
if ($process['success']) {
    return redirect()->route('admin.users.index')
        ->with('success', ResponseConst::SUCCESS_MESSAGE_CREATED);
}

return redirect()->back()
    ->withInput()
    ->with('error', $process['message'] ?? ResponseConst::DEFAULT_ERROR_MESSAGE);
```

## Auth Dan Access Control

- Login memakai Laravel session auth.
- Role user disimpan di kolom `users.access_type`.
- Daftar role berada di `App\Constants\UserConst`.
- Middleware `access_type` berada di `App\Http\Middleware\EnsureAccessType`.
- Route yang membutuhkan role khusus wajib menambahkan `access_type`.

Saat ini role aplikasi:

```php
UserConst::SUPERADMIN // 1
```

## Sidebar

Sidebar bersifat dinamis dari database.

Tabel terkait:

- `sidebar_menu_groups`
- `sidebar_menus`
- `sidebar_menu_accesses`

Aturan sidebar:

- Menu sidebar dibuat dan diatur lewat fitur Manajemen Sidebar.
- Icon menu berupa path Blade include.
- Route menu berupa Laravel named route.
- Akses menu berdasarkan `access_type`.
- Cache sidebar wajib di-flush setelah create, update, delete, atau sync access.
- Gunakan `SidebarMenuUsecase` untuk semua operasi sidebar.

## View Dan Blade

View admin berada di `resources/views/_admin`.

Aturan:

- Layout admin utama memakai `_admin._layout.app`.
- Gunakan Blade components di `resources/views/components/admin`.
- Gunakan `$page['title']` untuk judul halaman.
- Link internal admin sebaiknya memakai atribut `navigate`.
- Form yang ingin diproses SPA-like memakai atribut `navigate-form`.
- Jangan menulis query database di Blade.
- Logic presentasi ringan boleh ada di Blade, tapi logic bisnis tetap di usecase.

Pola view:

```blade
@extends('_admin._layout.app')

@section('title', 'Pengguna Aplikasi')

@section('content')
    <x-admin.page-header :title="'Data ' . $page['title']">
        <x-admin.button href="{{ route('admin.users.add') }}">
            Tambah Data
        </x-admin.button>
    </x-admin.page-header>
@endsection
```

## UI

UI menggunakan:

- Tailwind CSS v4 untuk utility styling.
- Preline UI untuk overlay, dropdown, accordion, dan komponen interaktif.
- Blade component untuk button, input, select, modal, card, table, dan empty state.
- Toastify untuk flash notification.
- NProgress untuk loading bar.
- Flatpickr untuk datepicker.
- Geist sebagai font utama.

Aturan UI:

- Reuse komponen yang sudah ada sebelum membuat komponen baru.
- Gunakan dark mode class `dark:` karena Preline dikonfigurasi class-based.
- Jaga konsistensi spacing, border radius, warna, dan state hover/focus dengan komponen yang sudah ada.
- Gunakan icon partial yang sudah ada di `_admin._layout.icons`.
- Untuk table, gunakan komponen `x-admin.table.*`.
- Untuk empty data, gunakan `x-admin.empty-state`.
- Hindari inline style kecuali benar-benar diperlukan untuk script/page-specific behavior.

## JavaScript

Entrypoint Vite:

- `resources/js/app.js`
- `resources/js/admin-custom.js`

Aturan JavaScript:

- Global admin behavior diletakkan di `admin-custom.js`.
- Page-specific script boleh diletakkan di `@push('scripts')`.
- Setelah DOM diganti oleh SPA-like navigation, Preline dan Flatpickr harus diinisialisasi ulang.
- Jangan mengganti struktur `#main-content` tanpa mempertimbangkan SPA-like navigation.
- Link dengan `a[navigate]` akan di-load via AJAX.
- Form dengan `form[navigate-form]` akan di-submit via AJAX.

## Asset Build

Asset dikelola Vite.

Command umum:

```bash
bun run dev
bun run build
```

Input Vite berada di `vite.config.js`.

## Testing

Aturan testing:

- Gunakan Pest.
- Buat test dengan `php artisan make:test --pest NamaTest`.
- Mayoritas fitur admin sebaiknya diuji sebagai feature test.
- Jalankan test dengan:

```bash
php artisan test --compact
```

Untuk perubahan PHP, jalankan formatter:

```bash
vendor/bin/pint --dirty --format agent
```

## Checklist Membuat Fitur CRUD Baru

1. Tambahkan schema di Drizzle jika butuh tabel baru.
2. Tambahkan konstanta tabel di `DatabaseConst`.
3. Buat usecase di `app/Usecase`.
4. Buat controller di `app/Http/Controllers/Admin`.
5. Tambahkan route di `routes/web.php`.
6. Buat view di `resources/views/_admin/{fitur}`.
7. Gunakan komponen Blade admin yang sudah tersedia.
8. Tambahkan menu sidebar lewat seeder atau Manajemen Sidebar.
9. Atur akses menu berdasarkan role.
10. Tambahkan test sesuai risiko fitur.
11. Jalankan Pint dan test sebelum selesai.

## Hal Yang Perlu Dihindari

- Query database di controller atau Blade.
- Hardcode nama tabel di usecase.
- Hardcode pesan sukses/error di controller jika sudah ada `ResponseConst`.
- Menambah dependency tanpa kebutuhan jelas.
- Membuat folder struktur baru tanpa alasan arsitektural.
- Menghapus test yang sudah ada tanpa persetujuan.
- Mengubah mekanisme SPA-like navigation tanpa menguji link, form, modal, toast, dan browser back/forward.
