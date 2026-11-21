<?php

declare(strict_types=1);

namespace FlipPlay\Models;

class UserCollection
{
    //ini
    /** @var array<string, Media> */
    private array $media = [];

    public function __construct(
        private string $userName
    ) {
        $this->userName = trim($this->userName);
    }

    public function getUserName(): string
    {
        return $this->userName;
    }
//ini
    public function addMedia(Media $media): bool
    {
        $key = strtolower($media->getTitle());

        if (isset($this->media[$key])) {
            return false;
        }

        $this->media[$key] = $media;

        return true;
    }

    /**
     * @return list<Media>
     */
    public function getMedia(): array
    {
        return array_values($this->media);
    }

    public function isEmpty(): bool
    {
        return $this->media === [];
    }

    public function count(): int
    {
        return count($this->media);
    }
}
