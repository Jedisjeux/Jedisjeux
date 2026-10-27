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

use App\Entity\BggProduct;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BggProductFactory
{
    private const MAX_PAYLOAD_LENGTH = 1048576;

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

        $bggProduct->setDescription($this->cleanDescription((string) $boardGame->description));

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
     * Creates a product from the payload sent by the BoardGameGeek bookmarklet
     * (base64url encoded and raw deflated JSON of the game page data).
     */
    public function createFromPayload(string $payload): BggProduct
    {
        $json = base64_decode(strtr($payload, '-_', '+/'), true);
        $json = false !== $json ? @gzinflate($json, self::MAX_PAYLOAD_LENGTH) : false;
        $data = false !== $json ? json_decode($json, true) : null;

        if (!is_array($data) || !is_string($data['name'] ?? null) || '' === $data['name']) {
            throw new BadRequestHttpException('Invalid BoardGameGeek data.');
        }

        $bggProduct = $this->createNew();
        $bggProduct->setName($data['name']);
        $bggProduct->setReleasedAtYear($this->getNumber($data, 'releasedAtYear'));
        $bggProduct->setMinDuration($this->getNumber($data, 'minDuration'));
        $bggProduct->setMaxDuration($this->getNumber($data, 'maxDuration'));
        $bggProduct->setAge($this->getNumber($data, 'age'));
        $bggProduct->setMinPlayerCount($this->getNumber($data, 'minPlayerCount'));
        $bggProduct->setMaxPlayerCount($this->getNumber($data, 'maxPlayerCount'));
        $bggProduct->setDescription($this->cleanDescription(html_entity_decode((string) ($data['description'] ?? ''), ENT_QUOTES | ENT_HTML5)));

        $imagePath = (string) ($data['imagePath'] ?? '');

        // Only BGG images are downloaded, to avoid fetching arbitrary URLs from the server
        if (1 === preg_match('#^https://[a-z0-9.-]*\.geekdo-images\.com/#i', $imagePath)) {
            $bggProduct->setImagePath($imagePath);
        }

        foreach ($this->getNames($data, 'mechanisms') as $mechanism) {
            $bggProduct->addMechanism($mechanism);
        }

        foreach ($this->getNames($data, 'designers') as $designer) {
            $bggProduct->addDesigner($designer);
        }

        foreach ($this->getNames($data, 'artists') as $artist) {
            $bggProduct->addArtist($artist);
        }

        foreach ($this->getNames($data, 'publishers') as $publisher) {
            $bggProduct->addPublisher($publisher);
        }

        return $bggProduct;
    }

    private function cleanDescription(string $description): string
    {
        $description = preg_replace("/\<br\s*\/?\>/i", "\n", $description);

        return strip_tags($description);
    }

    private function getNumber(array $data, string $key): ?string
    {
        $value = $data[$key] ?? null;

        if (!is_numeric($value) || (int) $value <= 0) {
            return null;
        }

        return (string) (int) $value;
    }

    private function getNames(array $data, string $key): array
    {
        $names = $data[$key] ?? [];

        return array_filter(is_array($names) ? $names : [], function ($name) {
            return is_string($name) && '' !== $name;
        });
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
