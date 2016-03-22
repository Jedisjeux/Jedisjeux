<?php

namespace AppBundle;

use AppBundle\DependencyInjection\Compiler\ServicesPass;
use Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AppBundle extends AbstractResourceBundle
{
    /**
     *  Add a comment to this line
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ServicesPass());
    }

    public static function getSupportedDrivers()
    {
        return array(
            SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
            SyliusResourceBundle::DRIVER_DOCTRINE_PHPCR_ODM,
        );
    }
}
