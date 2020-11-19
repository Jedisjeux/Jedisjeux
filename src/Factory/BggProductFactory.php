<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Factory;

use App\Entity\BggProduct;

class BggProductFactory
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $baseUrl;

    public function __construct(string $className, string $baseUrl)
    {
        $this->className = $className;
        $this->baseUrl = $baseUrl;
    }

    public function createNew(): BggProduct
    {
        return new $this->className();
    }

    public function createByPath(string $path): BggProduct
    {
        $boardGames = new \SimpleXMLElement(file_get_contents($this->getApiUrl($path)));
        $boardGame = $boardGames->boardgame[0];

        $bggProduct = $this->createNew();
        $bggProduct->setName((string) $boardGame->name);
        $bggProduct->setImagePath((string) $boardGame->image);
        $bggProduct->setReleasedAtYear($boardGame->yearpublished);
        $bggProduct->setMinDuration((string) $boardGame->minplaytime);
        $bggProduct->setMaxDuration((string) $boardGame->maxplaytime);
        $bggProduct->setAge((string) $boardGame->age);
        $bggProduct->setMinPlayerCount((string) $boardGame->minplayers);
        $bggProduct->setMaxPlayerCount((string) $boardGame->maxplayers);

        $description = preg_replace("/\<br\s*\/?\>/i", "\n", (string) $boardGame->description);
        $description = strip_tags($description);
        $bggProduct->setDescription($description);

        foreach ($boardGame->boardgamemechanic as $mechanism) {
            $bggProduct->addMechanism((string) $mechanism);
        }

        foreach ($boardGame->boardgamedesigner as $designer) {
            $bggProduct->addDesigner((string) $designer);
        }

        foreach ($boardGame->boardgameartist as $artist) {
            $bggProduct->addArtist((string) $artist);
        }

        foreach ($boardGame->boardgamepublisher as $publisher) {
            $bggProduct->addPublisher((string) $publisher);
        }

        return $bggProduct;
    }

    /**
     * @param string $url
     *
     * @return string
     */
    private function getApiUrl($url)
    {
        preg_match('/\/(?P<gameId>\d+)/', $url, $matches);

        return sprintf('%s/%s', $this->baseUrl, $matches['gameId']);
    }
}
