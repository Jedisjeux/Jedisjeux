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

use AppBundle\Entity\Redirection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadRedirectionsForImagesCommand extends AbstractLoadRedirectionsCommand
{
    const BATCH_SIZE = 20;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:redirections-for-images:load')
            ->setDescription('Load all redirections for images');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        $i = 0;

        foreach ($this->getImages() as $data) {
            $output->writeln(sprintf("Loading redirection for <comment>%s</comment> image", $data['source']));

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

        $output->writeln(sprintf("<info>%s</info>", "Redirects for news have been successfully loaded."));
    }

    /**
     * @return array
     */
    protected function getImages()
    {
        $query = <<<EOM
SELECT
  concat('/img/800/', old.img_nom) AS source,
  concat('/media/cache/resolve/full/uploads/img/', old.img_nom)                                               AS destination
FROM jedisjeux.jdj_images old
EOM;

        return $this->getManager()->getConnection()->fetchAll($query);
    }
}
