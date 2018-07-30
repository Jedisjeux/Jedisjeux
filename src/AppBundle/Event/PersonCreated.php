<?php

declare(strict_types=1);

namespace AppBundle\Event;

use AppBundle\Entity\Person;

final class PersonCreated
{
    /**
     * @var Person
     */
    private $person;

    /**
     * @param Person $person
     */
    private function __construct(Person $person)
    {
        $this->person = $person;
    }

    /**
     * @param Person $person
     *
     * @return self
     */
    public static function occur(Person $person)
    {
        return new self($person);
    }

    /**
     * @return Person
     */
    public function person(): Person
    {
        return $this->person;
    }
}
