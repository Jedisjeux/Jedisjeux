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

        foreach ($this->getPeople() as $data) {
            $output->writeln(sprintf("Loading redirection for <comment>%s</comment> page", $data['source']));

            $redirect = $this->createOrReplaceRedirection($data);
            $this->getManager()->persist($redirect);

            if (($i % self::BATCH_SIZE) === 0) {
                $this->getManager()->flush($redirect); // Executes all updates.
                $this->getManager()->clear(); // Detaches all objects from Doctrine!
            }

            ++$i;
        }

        $this->getManager()->flush();
        $this->getManager()->clear();

        $output->writeln(sprintf("<info>%s</info>", "Redirects for people have been successfully loaded."));
    }

    /**
     * @return array
     */
    protected function getPeople()
    {
        $query = <<<EOM
SELECT
  concat('/', old.href)                AS source,
  concat('/ludographie/', person.slug) AS destination
FROM jedisjeux.jdj_personnes old
  INNER JOIN jdj_person person
    ON old.id = person.id
EOM;

        return $this->getManager()->getConnection()->fetchAll($query);

    }
}
