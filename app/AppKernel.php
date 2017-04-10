<?php

use PSS\SymfonyMockerContainer\DependencyInjection\MockerContainer;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new JMS\TranslationBundle\JMSTranslationBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle(),

            new Sylius\Bundle\LocaleBundle\SyliusLocaleBundle(),
            new Sylius\Bundle\ProductBundle\SyliusProductBundle(),
            new Sylius\Bundle\UiBundle\SyliusUiBundle(),
            new Sylius\Bundle\UserBundle\SyliusUserBundle(),
            new Sylius\Bundle\CustomerBundle\SyliusCustomerBundle(),
            new Sylius\Bundle\MailerBundle\SyliusMailerBundle(),
            new Sylius\Bundle\ReviewBundle\SyliusReviewBundle(),
            new Sylius\Bundle\ResourceBundle\SyliusResourceBundle(),
            new Sylius\Bundle\AttributeBundle\SyliusAttributeBundle(),
            new Sylius\Bundle\TaxonomyBundle\SyliusTaxonomyBundle(),
            new winzou\Bundle\StateMachineBundle\winzouStateMachineBundle(),

            new Sonata\BlockBundle\SonataBlockBundle(),

            new Symfony\Cmf\Bundle\CoreBundle\CmfCoreBundle(),
            new Symfony\Cmf\Bundle\BlockBundle\CmfBlockBundle(),
            new Symfony\Cmf\Bundle\MediaBundle\CmfMediaBundle(),
            new Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle(),

            new Sylius\Bundle\GridBundle\SyliusGridBundle(),

            new FOS\RestBundle\FOSRestBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new FOS\ElasticaBundle\FOSElasticaBundle(),

            new Doctrine\Bundle\PHPCRBundle\DoctrinePHPCRBundle(),
            new Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),

            new Infinite\FormBundle\InfiniteFormBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new Knp\DoctrineBehaviors\Bundle\DoctrineBehaviorsBundle(),
            new Zenstruck\RedirectBundle\ZenstruckRedirectBundle(),

            // TODO remove these bundles
            new Bmatzner\JQueryUIBundle\BmatznerJQueryUIBundle(),
            new Bmatzner\JQueryBundle\BmatznerJQueryBundle(),
            new Bmatzner\FontAwesomeBundle\BmatznerFontAwesomeBundle(),

            new AppBundle\AppBundle(),
        );

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    /**
     * {@inheritdoc}
     */
    protected function getContainerBaseClass()
    {
        if (in_array($this->getEnvironment(), ['test', 'test_cached'], true)) {
            return MockerContainer::class;
        }

        return parent::getContainerBaseClass();
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        if ($this->isVagrantEnvironment()) {
            return '/dev/shm/jdj/cache/'.$this->environment;
        }

        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        if ($this->isVagrantEnvironment()) {
            return '/dev/shm/jdj/logs';
        }

        return dirname(__DIR__).'/var/logs';
    }

    /**
     * @return boolean
     */
    protected function isVagrantEnvironment()
    {
        return (getenv('HOME') === '/home/vagrant' || getenv('VAGRANT') === 'VAGRANT') && is_dir('/dev/shm');
    }
}
