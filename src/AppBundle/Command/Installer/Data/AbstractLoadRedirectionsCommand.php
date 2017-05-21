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
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
abstract class AbstractLoadRedirectionsCommand extends ContainerAwareCommand
{
    /**
     * @param array $data
     *
     * @return Redirection
     */
    protected function createOrReplaceRedirection(array $data)
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
     * @return EntityManager|object
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @return EntityRepository|object
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('app.repository.redirection');
    }

    /**
     * @return FactoryInterface|object
     */
    protected function getFactory()
    {
        return $this->getContainer()->get('app.factory.redirection');
    }
    /**
     * @return Router|object
     */
    protected function getRooter()
    {
        return $this->getContainer()->get('router');
    }
}
