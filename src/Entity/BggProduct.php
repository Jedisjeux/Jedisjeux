<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
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

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getImagePath(): string
    {
        return $this->imagePath;
    }

    /**
     * @param string $imagePath
     */
    public function setImagePath(string $imagePath): void
    {
        $this->imagePath = $imagePath;
    }

    /**
     * @return null|string
     */
    public function getReleasedAtYear(): ?string
    {
        return $this->releasedAtYear;
    }

    /**
     * @param null|string $releasedAtYear
     */
    public function setReleasedAtYear(?string $releasedAtYear): void
    {
        $this->releasedAtYear = $releasedAtYear;
    }

    /**
     * @return null|string
     */
    public function getMinDuration(): ?string
    {
        return $this->minDuration;
    }

    /**
     * @param null|string $minDuration
     */
    public function setMinDuration(?string $minDuration): void
    {
        $this->minDuration = $minDuration;
    }

    /**
     * @return null|string
     */
    public function getMaxDuration(): ?string
    {
        return $this->maxDuration;
    }

    /**
     * @param null|string $maxDuration
     */
    public function setMaxDuration(?string $maxDuration): void
    {
        $this->maxDuration = $maxDuration;
    }

    /**
     * @return null|string
     */
    public function getAge(): ?string
    {
        return $this->age;
    }

    /**
     * @param null|string $age
     */
    public function setAge(?string $age): void
    {
        $this->age = $age;
    }

    /**
     * @return null|string
     */
    public function getMinPlayerCount(): ?string
    {
        return $this->minPlayerCount;
    }

    /**
     * @param null|string $minPlayerCount
     */
    public function setMinPlayerCount(?string $minPlayerCount): void
    {
        $this->minPlayerCount = $minPlayerCount;
    }

    /**
     * @return null|string
     */
    public function getMaxPlayerCount(): ?string
    {
        return $this->maxPlayerCount;
    }

    /**
     * @param null|string $maxPlayerCount
     */
    public function setMaxPlayerCount(?string $maxPlayerCount): void
    {
        $this->maxPlayerCount = $maxPlayerCount;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return array
     */
    public function getMechanisms(): array
    {
        return $this->mechanisms;
    }

    /**
     * @param string $mechanism
     *
     * @return bool
     */
    public function hasMechanism(string $mechanism): bool
    {
        return in_array($mechanism, $this->mechanisms, true);
    }

    /**
     * @param string $mechanism
     */
    public function addMechanism(string $mechanism)
    {
        if (!$this->hasMechanism($mechanism)) {
            $this->mechanisms[] = $mechanism;
        }
    }

    /**
     * @param string $mechanism
     */
    public function removeMechanism(string $mechanism): void
    {
        if (false !== $key = array_search($mechanism, $this->mechanisms, true)) {
            unset($this->mechanisms[$key]);
            $this->mechanisms = array_values($this->mechanisms);
        }
    }

    /**
     * @return array
     */
    public function getDesigners(): array
    {
        return $this->designers;
    }

    /**
     * @param string $designer
     *
     * @return bool
     */
    public function hasDesigner(string $designer): bool
    {
        return in_array($designer, $this->designers, true);
    }

    /**
     * @param string $designer
     */
    public function addDesigner(string $designer)
    {
        if (!$this->hasDesigner($designer)) {
            $this->designers[] = $designer;
        }
    }

    /**
     * @param string $designer
     */
    public function removeDesigner(string $designer): void
    {
        if (false !== $key = array_search($designer, $this->designers, true)) {
            unset($this->designers[$key]);
            $this->designers = array_values($this->designers);
        }
    }

    /**
     * @return array
     */
    public function getArtists(): array
    {
        return $this->artists;
    }

    /**
     * @param string $artist
     *
     * @return bool
     */
    public function hasArtist(string $artist): bool
    {
        return in_array($artist, $this->artists, true);
    }

    /**
     * @param string $artist
     */
    public function addArtist(string $artist)
    {
        if (!$this->hasArtist($artist)) {
            $this->artists[] = $artist;
        }
    }

    /**
     * @param string $artist
     */
    public function removeArtist(string $artist): void
    {
        if (false !== $key = array_search($artist, $this->artists, true)) {
            unset($this->artists[$key]);
            $this->artists = array_values($this->artists);
        }
    }

    /**
     * @return array
     */
    public function getPublishers(): array
    {
        return $this->publishers;
    }

    /**
     * @param string $publisher
     *
     * @return bool
     */
    public function hasPublisher(string $publisher): bool
    {
        return in_array($publisher, $this->publishers, true);
    }

    /**
     * @param string $publisher
     */
    public function addPublisher(string $publisher)
    {
        if (!$this->hasPublisher($publisher)) {
            $this->publishers[] = $publisher;
        }
    }

    /**
     * @param string $publisher
     */
    public function removePublisher(string $publisher): void
    {
        if (false !== $key = array_search($publisher, $this->publishers, true)) {
            unset($this->publishers[$key]);
            $this->publishers = array_values($this->publishers);
        }
    }
}
