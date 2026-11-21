<?php

declare(strict_types=1);

namespace FlipPlay\Models;

use InvalidArgumentException;
//ini
class Anime extends Media
{
    public function __construct(
        string $title,
        array $genres,
        private int $episodes,
        private float $rating,
        private string $aura
    ) {
        parent::__construct($title, $genres);

        if ($this->episodes < 1) {
            throw new InvalidArgumentException('Jumlah episode harus lebih dari 0.');
        }

        if ($this->rating < 0 || $this->rating > 10) {
            throw new InvalidArgumentException('Rating harus berada di antara 0 dan 10.');
        }

        if (trim($this->aura) === '') {
            throw new InvalidArgumentException('Aura anime tidak boleh kosong.');
        }
    }

    public function getEpisodes(): int
    {
        return $this->episodes;
    }

    public function getRating(): float
    {
        return $this->rating;
    }

    public function getAura(): string
    {
        return $this->aura;
    }
//ini
    public function getDetails(): string
    {
        return sprintf(
            "Judul   : %s\nGenre   : %s\nEpisode : %d\nRating  : %.1f/10\nAura    : %s",
            $this->title,
            implode(', ', $this->genres),
            $this->episodes,
            $this->rating,
            $this->aura
        );
    }
}
