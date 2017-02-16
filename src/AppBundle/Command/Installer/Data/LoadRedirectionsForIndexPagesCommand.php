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
class LoadRedirectionsForIndexPagesCommand extends AbstractLoadRedirectionsCommand
{
    const BATCH_SIZE = 20;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:redirections-for-index-pages:load')
            ->setDescription('Load all redirections for index pages');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        $i = 0;

        foreach ($this->getPages() as $data) {
            $output->writeln(sprintf("Loading redirection for <comment>%s</comment> page", $data['source']));

            $redirect = $this->createOrReplaceRedirectionForPage($data);
            $this->getManager()->persist($redirect);

            if (($i % self::BATCH_SIZE) === 0) {
                $this->getManager()->flush($redirect); // Executes all updates.
                $this->getManager()->clear(); // Detaches all objects from Doctrine!
            }

            ++$i;
        }

        $this->getManager()->flush();
        $this->getManager()->clear();

        $output->writeln(sprintf("<info>%s</info>", "Redirects for pages have been successfully loaded."));
    }

    /**
     * @param array $data
     *
     * @return Redirection
     */
    protected function createOrReplaceRedirectionForPage(array $data)
    {
        /** @var Redirection $redirection */
        $redirection = $this->getRepository()->findOneBy(['source' => $data['source']]);

        if (null === $redirection) {
            $redirection = $this->getFactory()->createNew();
        }

        $redirection->setSource($data['source']);
        $redirection->setDestination($data['destination']);
        $redirection->setPermanent(true);

        return $redirection;
    }

    /**
     * @return array
     */
    protected function getPages()
    {
        return [
            [
                'source' => '/phpbb3/',
                'destination' => $this->getRooter()->generate('app_frontend_topic_index'),
            ],
            [
                'source' => '/phpbb3/viewforum.php',
                'destination' => $this->getRooter()->generate('app_frontend_topic_index'),
            ],
            [
                'source' => '/ludographie.php',
                'destination' => $this->getRooter()->generate('app_frontend_person_index'),
            ],
            [
                'source' => '/jeux_accueil.php',
                'destination' => $this->getRooter()->generate('sylius_product_index'),
            ],
            [
                'source' => '/jeux_categories.php',
                'destination' => $this->getRooter()->generate('sylius_product_index'),
            ],
            [
                'source' => '/parties_accueil.php',
                'destination' => $this->getRooter()->generate('app_frontend_game_play_index'),
            ],
            [
                'source' => '/parties.php',
                'destination' => $this->getRooter()->generate('app_frontend_game_play_index'),
            ],
            [
                'source' => '/sorties_jeux.php',
                'destination' => $this->getRooter()->generate('sylius_product_release_index'),
            ],
            [
                'source' => '/jeditest.php',
                'destination' => '/articles/categories/critiques',
            ],
            [
                'source' => '/jedinews.php',
                'destination' => '/articles/categories/actualites',
            ],
            [
                'source' => '/mentions_legales.php',
                'destination' => '/pages/mentions-legales',
            ],

        ];
    }
}
