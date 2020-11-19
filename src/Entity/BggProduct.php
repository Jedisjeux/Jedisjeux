<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

class BggProduct
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $imagePath;

    /**
     * @var string|null
     */
    private $releasedAtYear;

    /**
     * @var string|null
     */
    private $minDuration;

    /**
     * @var string|null
     */
    private $maxDuration;

    /**
     * @var string|null
     */
    private $age;

    /**
     * @var string|null
     */
    private $minPlayerCount;

    /**
     * @var string|null
     */
    private $maxPlayerCount;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var array
     */
    private $mechanisms = [];

    /**
     * @var array
     */
    private $designers = [];

    /**
     * @var array
     */
    private $artists = [];

    /**
     * @var array
     */
    private $publishers = [];

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getImagePath(): string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): void
    {
        $this->imagePath = $imagePath;
    }

    public function getReleasedAtYear(): ?string
    {
        return $this->releasedAtYear;
    }

    public function setReleasedAtYear(?string $releasedAtYear): void
    {
        $this->releasedAtYear = $releasedAtYear;
    }

    public function getMinDuration(): ?string
    {
        return $this->minDuration;
    }

    public function setMinDuration(?string $minDuration): void
    {
        $this->minDuration = $minDuration;
    }

    public function getMaxDuration(): ?string
    {
        return $this->maxDuration;
    }

    public function setMaxDuration(?string $maxDuration): void
    {
        $this->maxDuration = $maxDuration;
    }

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(?string $age): void
    {
        $this->age = $age;
    }

    public function getMinPlayerCount(): ?string
    {
        return $this->minPlayerCount;
    }

    public function setMinPlayerCount(?string $minPlayerCount): void
    {
        $this->minPlayerCount = $minPlayerCount;
    }

    public function getMaxPlayerCount(): ?string
    {
        return $this->maxPlayerCount;
    }

    public function setMaxPlayerCount(?string $maxPlayerCount): void
    {
        $this->maxPlayerCount = $maxPlayerCount;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getMechanisms(): array
    {
        return $this->mechanisms;
    }

    public function hasMechanism(string $mechanism): bool
    {
        return in_array($mechanism, $this->mechanisms, true);
    }

    public function addMechanism(string $mechanism)
    {
        if (!$this->hasMechanism($mechanism)) {
            $this->mechanisms[] = $mechanism;
        }
    }

    public function removeMechanism(string $mechanism): void
    {
        if (false !== $key = array_search($mechanism, $this->mechanisms, true)) {
            unset($this->mechanisms[$key]);
            $this->mechanisms = array_values($this->mechanisms);
        }
    }

    public function getDesigners(): array
    {
        return $this->designers;
    }

    public function hasDesigner(string $designer): bool
    {
        return in_array($designer, $this->designers, true);
    }

    public function addDesigner(string $designer)
    {
        if (!$this->hasDesigner($designer)) {
            $this->designers[] = $designer;
        }
    }

    public function removeDesigner(string $designer): void
    {
        if (false !== $key = array_search($designer, $this->designers, true)) {
            unset($this->designers[$key]);
            $this->designers = array_values($this->designers);
        }
    }

    public function getArtists(): array
    {
        return $this->artists;
    }

    public function hasArtist(string $artist): bool
    {
        return in_array($artist, $this->artists, true);
    }

    public function addArtist(string $artist)
    {
        if (!$this->hasArtist($artist)) {
            $this->artists[] = $artist;
        }
    }

    public function removeArtist(string $artist): void
    {
        if (false !== $key = array_search($artist, $this->artists, true)) {
            unset($this->artists[$key]);
            $this->artists = array_values($this->artists);
        }
    }

    public function getPublishers(): array
    {
        return $this->publishers;
    }

    public function hasPublisher(string $publisher): bool
    {
        return in_array($publisher, $this->publishers, true);
    }

    public function addPublisher(string $publisher)
    {
        if (!$this->hasPublisher($publisher)) {
            $this->publishers[] = $publisher;
        }
    }

    public function removePublisher(string $publisher): void
    {
        if (false !== $key = array_search($publisher, $this->publishers, true)) {
            unset($this->publishers[$key]);
            $this->publishers = array_values($this->publishers);
        }
    }
}
