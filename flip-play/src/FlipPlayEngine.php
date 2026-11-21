<?php

declare(strict_types=1);

namespace FlipPlay\Services;

use FlipPlay\Models\Media;
use FlipPlay\Models\UserCollection;

class FlipPlayEngine
{
    /** @var array<string, Media> */
    private array $library = [];
//ini
    public function addMedia(Media $media): bool
    {
        $key = strtolower($media->getTitle());

        if (isset($this->library[$key])) {
            return false;
        }

        $this->library[$key] = $media;

        return true;
    }

    /**
     * @return list<Media>
     */
    public function getAllMedia(): array
    {
        return array_values($this->library);
    }

    /**
     * @return list<Media>
     */
    public function searchMedia(string $keyword): array
    {
        $keyword = trim($keyword);

        if ($keyword === '') {
            return [];
        }

        return array_values(array_filter(
            $this->library,
            static fn (Media $media): bool => stripos($media->getTitle(), $keyword) !== false
        ));
    }

    public function findMediaByTitle(string $title): ?Media
    {
        return $this->library[strtolower(trim($title))] ?? null;
    }

    public function addMediaToCollection(string $title, UserCollection $collection): bool
    {
        $media = $this->findMediaByTitle($title);

        if ($media === null) {
            return false;
        }

        return $collection->addMedia($media);
    }
}
