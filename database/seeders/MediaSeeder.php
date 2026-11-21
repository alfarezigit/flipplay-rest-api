<?php

namespace Database\Seeders;

use App\Models\Media;
use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        $media = [
            [
                'title' => 'Inception',
                'type' => 'Movie',
                'genre' => 'Science Fiction',
                'release_year' => 2010,
                'description' => 'Seorang pencuri memasuki mimpi untuk menanamkan sebuah ide.',
            ],
            [
                'title' => 'Blue Planet',
                'type' => 'Series',
                'genre' => 'Documentary',
                'release_year' => 2001,
                'description' => 'Serial dokumenter yang menjelajahi kehidupan di lautan.',
            ],
            [
                'title' => 'Kind of Blue',
                'type' => 'Music',
                'genre' => 'Jazz',
                'release_year' => 1959,
                'description' => 'Album jazz klasik karya Miles Davis.',
            ],
            [
                'title' => 'The Daily',
                'type' => 'Podcast',
                'genre' => 'News',
                'release_year' => 2017,
                'description' => 'Podcast berita harian dengan laporan mendalam.',
            ],
            [
                'title' => 'Spirited Away',
                'type' => 'Movie',
                'genre' => 'Animation',
                'release_year' => 2001,
                'description' => 'Petualangan seorang anak di dunia roh yang penuh keajaiban.',
            ],
            [
                'title' => 'Planet Earth',
                'type' => 'Series',
                'genre' => 'Nature',
                'release_year' => 2006,
                'description' => 'Dokumenter alam tentang berbagai habitat di bumi.',
            ],
            [
                'title' => 'Random Access Memories',
                'type' => 'Music',
                'genre' => 'Electronic',
                'release_year' => 2013,
                'description' => 'Album Daft Punk yang merayakan musik dansa dan analog.',
            ],
            [
                'title' => '99% Invisible',
                'type' => 'Podcast',
                'genre' => 'Design',
                'release_year' => 2010,
                'description' => 'Cerita tentang desain dan arsitektur yang sering terlewat.',
            ],
            [
                'title' => 'The Grand Budapest Hotel',
                'type' => 'Movie',
                'genre' => 'Comedy',
                'release_year' => 2014,
                'description' => 'Petualangan penuh warna di sebuah hotel di Eropa.',
            ],
            [
                'title' => 'Stranger Things',
                'type' => 'Series',
                'genre' => 'Science Fiction',
                'release_year' => 2016,
                'description' => 'Sekelompok sahabat menghadapi misteri dari dunia lain.',
            ],
        ];

        foreach ($media as $item) {
            Media::factory()->create($item);
        }
    }
}
