@extends('_admin._layout.app')
@section('title', 'Detail ' . $page['title'])

@section('content')
    <div class="py-4">
        <h1>Detail {{ $page['title'] }}</h1>
        <a href="{{ route('admin.' . $page['route'] . '.index') }}">Kembali</a>
    </div>

    <div>
        <p><strong>Nama:</strong> {{ $data->name }}</p>
        <p><strong>Deskripsi:</strong> {{ $data->description }}</p>
        <p><strong>Waktu Mulai:</strong> {{ \Carbon\Carbon::parse($data->start_time)->format('Y-m-d H:i') }}</p>
        <p><strong>Waktu Selesai:</strong> {{ \Carbon\Carbon::parse($data->end_time)->format('Y-m-d H:i') }}</p>
        <p><strong>Status:</strong> {{ $data->status }}</p>
        <p><strong>Dibuat Pada:</strong> {{ \Carbon\Carbon::parse($data->created_at)->format('Y-m-d H:i') }}</p>
        <p><strong>Dibuat Oleh:</strong> {{ $data->created_by }}</p>
    </div>
@endsection
