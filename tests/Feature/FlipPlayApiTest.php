<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlipPlayApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_media_can_be_created_searched_and_partially_updated(): void
    {
        $media = Media::factory()->create(['title' => 'The Test Film']);
        $otherMedia = Media::factory()->create(['title' => 'Another Test Film']);

        $this->getJson('/api/media?title=Test')
            ->assertOk()
            ->assertJsonPath('data.0.title', 'The Test Film');

        $this->patchJson('/api/media/'.$media->id, ['title' => 'The Test Film'])
            ->assertOk()
            ->assertJsonPath('data.title', 'The Test Film');

        $this->patchJson('/api/media/'.$media->id, ['title' => $otherMedia->title])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('title');

        $this->patchJson('/api/media/'.$media->id, ['description' => 'Deskripsi baru'])
            ->assertOk()
            ->assertJsonPath('data.description', 'Deskripsi baru');
    }

    public function test_media_creation_rejects_an_empty_or_duplicate_title(): void
    {
        $existing = Media::factory()->create(['title' => 'Existing Title']);

        $this->postJson('/api/media', [
            'title' => '',
            'type' => 'Movie',
            'genre' => 'Drama',
            'release_year' => 2020,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('title')
            ->assertJsonPath('errors.title.0', 'Judul media wajib diisi.');

        $this->postJson('/api/media', [
            'title' => $existing->title,
            'type' => 'Movie',
            'genre' => 'Drama',
            'release_year' => 2020,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('title');
    }

    public function test_collection_addition_rejects_duplicates_and_can_remove_media(): void
    {
        $user = User::factory()->create();
        $media = Media::factory()->create();
        $url = '/api/users/'.$user->id.'/collection';

        $this->postJson($url, ['media_id' => $media->id])
            ->assertOk()
            ->assertJsonPath('data.collection.0.id', $media->id);

        $this->postJson($url, ['media_id' => $media->id])
            ->assertStatus(409)
            ->assertJsonPath('message', 'Media sudah ada di koleksi');

        $this->deleteJson($url.'/'.$media->id)
            ->assertOk()
            ->assertJsonPath('message', 'Media berhasil dihapus dari koleksi');

        $this->deleteJson($url.'/'.$media->id)->assertNotFound();
    }

    public function test_unknown_media_and_users_return_not_found(): void
    {
        $this->getJson('/api/media/999999')->assertNotFound();
        $this->getJson('/api/users/999999')->assertNotFound();
    }
}
