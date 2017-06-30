<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\EventSubscriber;

use AppBundle\Entity\Person;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class AddPersonFormSubscriber implements EventSubscriberInterface
{
    /**
     * @var FactoryInterface
     */
    protected $personFactory;

    /**
     * @var EntityRepository
     */
    protected $personRepository;

    /**
     * AddPersonFormSubscriber constructor.
     *
     * @param FactoryInterface $personFactory
     * @param EntityRepository $personRepository
     */
    public function __construct(FactoryInterface $personFactory, EntityRepository $personRepository)
    {
        $this->personFactory = $personFactory;
        $this->personRepository = $personRepository;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::SUBMIT => 'submit',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function submit(FormEvent $event)
    {
        $form = $event->getForm();
        $designersData = $form->get('designers')->getNormData();

        foreach ($designersData as $personId) {
            $person = $this->personRepository->find($personId);

            if (null === $person) {
                /** @var Person $person */
                $person = $this->personFactory->createNew();
                $person->setLastName($this->getLastNameByConcatenatedNames($personId));
            }
        }
    }

    /**
     * @param string $names
     *
     * @return string
     */
    protected function getLastNameByConcatenatedNames($names)
    {
        $nameParts = explode(' ', $names);

        if (count($nameParts) === 1) {
            return $nameParts[0];
        }

        return implode(' ', array_slice($nameParts, 1));
    }
}
