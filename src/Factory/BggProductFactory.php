<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Factory;

use App\Utils\BggProduct;

class BggProductFactory
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var \SimpleXMLElement
     */
    private $boardGame;

    /**
     * @param string $baseUrl
     */
    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param string $path
     *
     * @return BggProduct
     */
    public function createByPath(string $path): BggProduct
    {
        $boardGames = new \SimpleXMLElement(file_get_contents($this->getApiUrl($path)));
        $this->boardGame = $boardGames->boardgame[0];

        $bggProduct = new BggProduct();
        $bggProduct->setName((string) $this->boardGame->name);
        $bggProduct->setImagePath((string) $this->boardGame->image);
        $bggProduct->setReleasedAtYear($this->boardGame->yearpublished);
        $bggProduct->setMinDuration((string) $this->boardGame->minplaytime);
        $bggProduct->setMaxDuration((string) $this->boardGame->maxplaytime);
        $bggProduct->setAge((string) $this->boardGame->age);
        $bggProduct->setMinPlayerCount((string) $this->boardGame->minplayers);
        $bggProduct->setMaxPlayerCount((string) $this->boardGame->maxplayers);

        $description = preg_replace("/\<br\s*\/?\>/i", "\n", (string) $this->boardGame->description);
        $description = strip_tags($description);
        $bggProduct->setDescription($description);

        foreach ($this->boardGame->boardgamemechanic as $mechanism) {
            $bggProduct->addMechanism((string) $mechanism);
        }

        foreach ($this->boardGame->boardgamedesigner as $designer) {
            $bggProduct->addDesigner((string) $designer);
        }

        foreach ($this->boardGame->boardgameartist as $artist) {
            $bggProduct->addArtist((string) $artist);
        }

        foreach ($this->boardGame->boardgamepublisher as $publisher) {
            $bggProduct->addPublisher((string) $publisher);
        }
    }

    /**
     * @param string $url
     *
     * @return string
     */
    private function getApiUrl($url)
    {
        preg_match('/\/(?P<gameId>\d+)/', $url, $matches);

        return $this->baseUrl.$matches['gameId'];
    }
}
