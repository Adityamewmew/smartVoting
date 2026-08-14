<?php

use Illuminate\Support\Facades\Route;

test('kiosk routes are properly registered', function () {
    expect(Route::has('kiosk.start'))->toBeTrue()
        ->and(Route::has('kiosk.generate'))->toBeTrue()
        ->and(Route::has('kiosk.vote'))->toBeTrue()
        ->and(Route::has('kiosk.submit'))->toBeTrue()
        ->and(Route::has('kiosk.expire'))->toBeTrue()
        ->and(Route::has('landing.election'))->toBeTrue();
});

test('voter candidate card component renders booth variant with tactile button', function () {
    $candidate = (object) [
        'id' => 1,
        'order_number' => 1,
        'chairman_name' => 'Dr. Aria Setiawan',
        'vice_chairman_name' => 'Budi Santoso, M.Sc',
        'photo_path' => null,
        'vision' => 'Visi uji coba',
        'mission' => "Misi 1\nMisi 2",
    ];

    $view = $this->blade(
        '<x-voter.candidate-card :candidate="$candidate" variant="booth" />',
        ['candidate' => $candidate]
    );

    $view->assertSee('PASLON 01')
        ->assertSee('Dr. Aria Setiawan')
        ->assertSee('Budi Santoso, M.Sc')
        ->assertSee('Lihat Visi & Misi')
        ->assertSee('Pilih Kandidat 1');
});

test('voter visi misi modal component renders vision quote and mission items', function () {
    $candidate = (object) [
        'id' => 1,
        'vision' => 'Mewujudkan inovasi berkelanjutan',
        'mission' => "Membangun kolaborasi\nTransparansi keputusan",
    ];

    $view = $this->blade(
        '<x-voter.visi-misi-modal :candidate="$candidate" />',
        ['candidate' => $candidate]
    );

    $view->assertSee('Visi & Misi')
        ->assertSee('Mewujudkan inovasi berkelanjutan')
        ->assertSee('Membangun kolaborasi')
        ->assertSee('Transparansi keputusan')
        ->assertSee('Tutup');
});
