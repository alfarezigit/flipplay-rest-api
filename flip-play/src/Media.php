<?php

declare(strict_types=1);

namespace FlipPlay\Models;

use InvalidArgumentException;

abstract class Media
{
    /**
     * @param list<string> $genres
     */
    public function __construct(
        protected string $title,
        protected array $genres
    ) {
        if (trim($this->title) === '') {
            throw new InvalidArgumentException('Judul media tidak boleh kosong.');
        }

        if ($this->genres === []) {
            throw new InvalidArgumentException('Media harus memiliki minimal satu genre.');
        }

        foreach ($this->genres as $genre) {
            if (!is_string($genre) || trim($genre) === '') {
                throw new InvalidArgumentException('Setiap genre harus berupa teks yang valid.');
            }
        }
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return list<string>
     */
    public function getGenres(): array
    {
        return $this->genres;
    }
//ini
    abstract public function getDetails(): string;
}
