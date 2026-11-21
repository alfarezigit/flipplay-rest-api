<?php

declare(strict_types=1);

use FlipPlay\Models\Anime;
use FlipPlay\Models\UserCollection;
use FlipPlay\Services\FlipPlayEngine;

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "Program ini hanya dapat dijalankan melalui CLI.\n");
    exit(1);
}

require_once __DIR__ . '/src/Media.php';
require_once __DIR__ . '/src/Anime.php';
require_once __DIR__ . '/src/UserCollection.php';
require_once __DIR__ . '/src/FlipPlayEngine.php';

$supportsColor = function_exists('stream_isatty') && stream_isatty(STDOUT);
$paint = static function (string $text, string $color) use ($supportsColor): string {
    return $supportsColor ? "\033[{$color}m{$text}\033[0m" : $text;
};
$section = static function (string $title) use ($paint): void {
    echo "\n" . $paint(str_repeat('-', 62), '90') . "\n";
    echo $paint("  {$title}", '1;95') . "\n";
    echo $paint(str_repeat('-', 62), '90') . "\n";
};

$engine = new FlipPlayEngine();
$userCollection = new UserCollection('Alya');

$section('FLIPPLAY | SIMULASI ENGINE LIBRARY ANIME');
echo $paint("  Selamat datang, {$userCollection->getUserName()}!\n", '96');

$section('1. MENAMBAHKAN ANIME KE LIBRARY');
$animeList = [
    new Anime('Frieren: Beyond Journey\'s End', ['Adventure', 'Drama', 'Fantasy'], 28, 9.3, 'Melankolis'),
    new Anime('Haikyu!!', ['Sports', 'Comedy'], 25, 8.7, 'Enerjik'),
    new Anime('Spy x Family', ['Action', 'Comedy'], 25, 8.5, 'Santai'),
    new Anime('Violet Evergarden', ['Drama', 'Fantasy'], 13, 8.9, 'Melankolis'),
];

foreach ($animeList as $anime) {
    $added = $engine->addMedia($anime);
    $status = $added ? $paint('DITAMBAHKAN', '92') : $paint('SUDAH ADA', '93');
    echo sprintf("  %-12s %s\n", "[{$status}]", $anime->getTitle());
}

$section('2. DAFTAR SEMUA MEDIA');
foreach ($engine->getAllMedia() as $index => $media) {
    $rating = $media instanceof Anime ? number_format($media->getRating(), 1) : 'N/A';

    echo sprintf(
        "  %d. %-38s %s\n",
        $index + 1,
        $media->getTitle(),
        $paint($rating === 'N/A' ? $rating : '★ ' . $rating, '93')
    );
}

$searchKeyword = 'haikyu';
$section("3. HASIL PENCARIAN: \"{$searchKeyword}\"");
$searchResults = $engine->searchMedia($searchKeyword);

if ($searchResults === []) {
    echo "  Tidak ada media yang cocok dengan kata kunci tersebut.\n";
} else {
    foreach ($searchResults as $media) {
        echo '  Ditemukan: ' . $media->getTitle() . "\n";
    }
}

$selectedTitle = 'Frieren: Beyond Journey\'s End';
$section('4. DETAIL MEDIA & TAMBAH KE KOLEKSI');
$selectedMedia = $engine->findMediaByTitle($selectedTitle);

if ($selectedMedia === null) {
    echo "  Media tidak ditemukan di library.\n";
} else {
    echo $selectedMedia->getDetails() . "\n\n";
    $addedToCollection = $engine->addMediaToCollection($selectedTitle, $userCollection);

    echo $addedToCollection
        ? $paint("  Berhasil ditambahkan ke koleksi {$userCollection->getUserName()}.\n", '92')
        : $paint("  Media sudah ada di koleksi {$userCollection->getUserName()}.\n", '93');
}

$section("5. KOLEKSI MEDIA MILIK {$userCollection->getUserName()}");
if ($userCollection->isEmpty()) {
    echo "  Koleksi masih kosong.\n";
} else {
    foreach ($userCollection->getMedia() as $index => $media) {
        echo sprintf("  %d. %s\n", $index + 1, $media->getTitle());
    }

    echo $paint("  Total koleksi: {$userCollection->count()} media\n", '96');
}

echo "\n" . $paint("  Simulasi selesai. Terima kasih sudah mencoba FlipPlay!\n", '1;95');
