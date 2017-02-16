<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Entity\Person;
use AppBundle\Entity\Redirection;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadRedirectionsForPeoplesCommand extends AbstractLoadRedirectionsCommand
{
    const BATCH_SIZE = 1;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:redirections-for-people:load')
            ->setDescription('Load all redirections for people');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        $i = 0;

        foreach ($this->createListQueryBuilder()->getQuery()->iterate() as $row) {
            /** @var Person $person */
            $person = $row[0];

            $output->writeln(sprintf("Loading redirection for <comment>%s</comment> person", '/'.$person->getFullName()));

            $redirect = $this->createOrReplaceRedirectionForPerson($person);
            $this->getManager()->persist($redirect);

            if (($i % self::BATCH_SIZE) === 0) {
                $this->getManager()->flush($redirect); // Executes all updates.
                $this->getManager()->clear(); // Detaches all objects from Doctrine!
            }

            ++$i;
        }

        $this->getManager()->flush();
        $this->getManager()->clear();

        $output->writeln(sprintf("<info>%s</info>", "Redirects for products have been successfully loaded."));
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function createListQueryBuilder()
    {
        $queryBuilder = $this->getPersonRepository()->createQueryBuilder('o');
        $queryBuilder
            ->andWhere($queryBuilder->expr()->isNotNull('o.oldHref'));

        return $queryBuilder;
    }

    /**
     * @param Person $person
     *
     * @return Redirection
     */
    protected function createOrReplaceRedirectionForPerson(Person $person)
    {
        $oldHref = '/'.$person->getOldHref();

        /** @var Redirection $redirection */
        $redirection = $this->getRepository()->findOneBy(['source' => $oldHref]);

        if (null === $redirection) {
            $redirection = $this->getFactory()->createNew();
        }

        $destination = $this->getRooter()->generate('app_frontend_person_show', ['slug' => $person->getSlug()]);

        $redirection->setSource($oldHref);
        $redirection->setDestination($destination);
        $redirection->setPermanent(true);

        return $redirection;
    }

    /**
     * @return EntityRepository|object
     */
    protected function getPersonRepository()
    {
        return $this->getContainer()->get('app.repository.person');
    }
}
