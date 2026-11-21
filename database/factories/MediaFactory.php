<?php

namespace Database\Factories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Media>
 */
class MediaFactory extends Factory
{
    protected $model = Media::class;

    public function definition(): array
    {
        $catalog = [
            ['type' => 'Movie', 'genre' => 'Science Fiction'],
            ['type' => 'Series', 'genre' => 'Drama'],
            ['type' => 'Music', 'genre' => 'Pop'],
            ['type' => 'Podcast', 'genre' => 'Technology'],
            ['type' => 'Movie', 'genre' => 'Adventure'],
            ['type' => 'Series', 'genre' => 'Comedy'],
            ['type' => 'Music', 'genre' => 'Jazz'],
            ['type' => 'Podcast', 'genre' => 'Culture'],
        ];
        $details = fake()->randomElement($catalog);

        return [
            'title' => fake()->unique()->sentence(3),
            'type' => $details['type'],
            'genre' => $details['genre'],
            'release_year' => fake()->numberBetween(1980, (int) date('Y')),
            'description' => fake()->paragraph(),
        ];
    }
}
