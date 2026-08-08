@extends('_admin._layout.app')
@section('title', 'Tambah ' . $page['title'])

@section('content')
    <div class="py-4">
        <h1>Tambah {{ $page['title'] }}</h1>
        <a href="{{ route('admin.' . $page['route'] . '.index') }}">Kembali</a>
    </div>

    <form action="{{ route('admin.' . $page['route'] . '.doAdd') }}" method="POST">
        @csrf
        <div>
            <label for="name">Nama Pemilihan</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name') <span>{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description">{{ old('description') }}</textarea>
            @error('description') <span>{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="start_time">Waktu Mulai</label>
            <input type="datetime-local" id="start_time" name="start_time" value="{{ old('start_time') }}" required>
            @error('start_time') <span>{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="end_time">Waktu Selesai</label>
            <input type="datetime-local" id="end_time" name="end_time" value="{{ old('end_time') }}" required>
            @error('end_time') <span>{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
            @error('status') <span>{{ $message }}</span> @enderror
        </div>
        <button type="submit">Simpan</button>
    </form>
@endsection
