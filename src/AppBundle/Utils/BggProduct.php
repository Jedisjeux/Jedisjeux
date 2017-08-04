<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 29/06/2016
 * Time: 13:46
 */

namespace AppBundle\Utils;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class BggProduct
{
    const API_BASE_URL = 'https://www.boardgamegeek.com/xmlapi/boardgame/';

    /**
     * @var \SimpleXMLElement
     */
    protected $boardGame;

    /**
     * ProductFromBggPath constructor.
     *
     * @param $url
     */
    public function __construct($url)
    {
        $boardGames = new \SimpleXMLElement(file_get_contents($this->getApiUrl($url)));
        $this->boardGame = $boardGames->boardgame[0];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return (string)$this->boardGame->name;
    }

    /**
     * @return string
     */
    public function getReleasedAtYear()
    {
        return $this->boardGame->yearpublished;
    }

    /**
     * @return array
     */
    public function getAuteurs()
    {
        return $this->getPeople($this->boardGame->boardgamedesigner);
    }

    /**
     * @return array
     */
    public function getIllustrateurs()
    {
        return $this->getPeople($this->boardGame->boardgameartist);
    }

    /**
     * @return array
     */
    public function getEditeurs()
    {
        return $this->getPeople($this->boardGame->boardgamepublisher);
    }

    public function getDuration()
    {
        return (string)$this->boardGame->minplaytime;
    }

    public function getAge()
    {
        return (string)$this->boardGame->age;
    }

    public function getNbJoueursMin()
    {
        return (string)$this->boardGame->minplayers;
    }

    public function getNbJoueursMax()
    {
        return (string)$this->boardGame->maxplayers;
    }

    /**
     * @return array
     */
    public function getMecanismes()
    {
        $mechanisms = array();

        foreach ($this->boardGame->boardgamemechanic as $row) {
            $mechanisms[] = (string)$row;
        }

        return $mechanisms;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        $description = preg_replace("/\<br\s*\/?\>/i", "\n", (string)$this->boardGame->description);

        return strip_tags($description);
    }

    /**
     * @return array
     */
    public function getDesigners()
    {
        $designers = array();

        foreach ($this->boardGame->boardgamedesigner as $row) {
            $designers[] = (string)$row;
        }

        return $designers;
    }

    /**
     * @return array
     */
    public function getArtists()
    {
        $artists = array();

        foreach ($this->boardGame->boardgameartist as $row) {
            $artists[] = (string)$row;
        }

        return $artists;
    }

    /**
     * @return array
     */
    public function getPublishers()
    {
        $publishers = array();

        foreach ($this->boardGame->boardgamepublisher as $row) {
            $publishers[] = (string)$row;
        }

        return $publishers;
    }

    /**
     * @param $peopleNode
     *
     * @return array
     */
    protected function getPeople($peopleNode)
    {
        $people = array();

        foreach ($peopleNode as $row) {
            $people[] = (string)$row;
        }

        return $people;
    }

    protected function getApiUrl($url)
    {
        preg_match('/\/(?P<gameId>\d+)/', $url, $matches);
        return self::API_BASE_URL.$matches['gameId'];
    }
}