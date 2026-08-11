<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Hasil Suara - {{ $election->name }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (via CDN for print view to ensure standalone rendering) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Geist', sans-serif; background-color: white; color: black; }
        @media print {
            @page { margin: 2cm; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-white text-black p-8 max-w-4xl mx-auto">
    
    <div class="text-center mb-8 border-b-2 border-black pb-6">
        <h1 class="text-3xl font-bold uppercase tracking-wider mb-2">Laporan Hasil Pemilihan</h1>
        <h2 class="text-xl font-semibold">{{ $election->name }}</h2>
        <p class="text-sm mt-2 text-gray-600">{{ $election->description ?? '' }}</p>
    </div>

    <div class="flex justify-between items-end mb-6">
        <div>
            <p class="text-sm font-semibold">Waktu Cetak:</p>
            <p class="text-sm">{{ now()->translatedFormat('l, d F Y H:i:s') }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm font-semibold">Total Suara Masuk:</p>
            <p class="text-2xl font-bold">{{ $totalVotes }} Suara</p>
        </div>
    </div>

    <table class="w-full text-left border-collapse border border-gray-300 mb-12">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 px-4 py-3 text-center w-16">No</th>
                <th class="border border-gray-300 px-4 py-3">Nama Pasangan Calon</th>
                <th class="border border-gray-300 px-4 py-3 text-right w-32">Jumlah Suara</th>
                <th class="border border-gray-300 px-4 py-3 text-right w-32">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @forelse($candidates as $candidate)
                @php
                    $percentage = $totalVotes > 0 ? round(($candidate->vote_count / $totalVotes) * 100, 2) : 0;
                @endphp
                <tr>
                    <td class="border border-gray-300 px-4 py-3 text-center font-bold">{{ $candidate->order_number }}</td>
                    <td class="border border-gray-300 px-4 py-3">
                        <div class="font-semibold">{{ $candidate->chairman_name }}</div>
                        <div class="text-sm text-gray-600">Wakil: {{ $candidate->vice_chairman_name }}</div>
                    </td>
                    <td class="border border-gray-300 px-4 py-3 text-right font-semibold">{{ $candidate->vote_count }}</td>
                    <td class="border border-gray-300 px-4 py-3 text-right">{{ $percentage }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="border border-gray-300 px-4 py-3 text-center text-gray-500">Belum ada data kandidat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="flex justify-end mt-16">
        <div class="text-center w-64">
            <p class="mb-20">Mengetahui,<br>Ketua Panitia Pemilihan</p>
            <p class="font-bold underline">( ......................................... )</p>
        </div>
    </div>

    <div class="fixed bottom-4 right-4 no-print flex gap-4">
        <button onclick="window.close()" class="px-4 py-2 bg-gray-500 text-white rounded shadow hover:bg-gray-600 transition">Tutup</button>
        <button onclick="window.print()" class="px-6 py-2 glass-button text-white rounded shadow transition">Cetak Sekarang</button>
    </div>

    <script>
        // Otomatis trigger print saat halaman diload
        window.onload = function() {
            setTimeout(() => {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
