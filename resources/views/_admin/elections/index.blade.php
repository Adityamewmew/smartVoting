@extends('_admin._layout.app')
@section('title', 'Data ' . $page['title'])

@section('content')
    <div class="grid gap-3 md:flex md:justify-between md:items-center py-4">
        <h1>Data {{ $page['title'] }}</h1>
        <a href="{{ route('admin.' . $page['route'] . '.add') }}">Tambah Data</a>
    </div>

    <form action="{{ route('admin.' . $page['route'] . '.index') }}" method="GET" navigate-form>
        <input type="text" name="keywords" value="{{ $keywords ?? '' }}" placeholder="Cari...">
        <button type="submit">Cari</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Mulai</th>
                <th>Selesai</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $d)
                <tr>
                    <td>{{ $d->name }}</td>
                    <td>{{ $d->description }}</td>
                    <td>{{ \Carbon\Carbon::parse($d->start_time)->format('Y-m-d H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($d->end_time)->format('Y-m-d H:i') }}</td>
                    <td>{{ $d->status }}</td>
                    <td>
                        <a href="{{ route('admin.' . $page['route'] . '.detail', $d->id) }}">Detail</a>
                        <a href="{{ route('admin.' . $page['route'] . '.update', $d->id) }}">Edit</a>
                        <form action="{{ route('admin.' . $page['route'] . '.doDelete', $d->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin hapus data ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Data tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if (count($data) > 0 && $data->hasPages())
        {{ $data->links() }}
    @endif
@endsection
