<?php

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
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Sonata\IntlBundle\SonataIntlBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new FOS\CommentBundle\FOSCommentBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle($this),
            //new JMS\TranslationBundle\JMSTranslationBundle(),
            new \Liip\ImagineBundle\LiipImagineBundle(),
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new Gregwar\FormBundle\GregwarFormBundle(),
            new FOS\ElasticaBundle\FOSElasticaBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle(),
            new Craue\FormFlowBundle\CraueFormFlowBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),

            //JDJ
            new JDJ\FoundationBundle\JDJFoundationBundle(),
            new JDJ\WebBundle\JDJWebBundle(),
            new JDJ\ComptaBundle\JDJComptaBundle(),
            new JDJ\JeuBundle\JDJJeuBundle(),
            new JDJ\LudographieBundle\JDJLudographieBundle(),
            new JDJ\UserBundle\JDJUserBundle(),
            new JDJ\CommentBundle\JDJCommentBundle(),
            new JDJ\PartieBundle\JDJPartieBundle(),
            new JDJ\UserReviewBundle\JDJUserReviewBundle(),
            new JDJ\CoreBundle\JDJCoreBundle(),
            new JDJ\SearchBundle\JDJSearchBundle(),
            new JDJ\CollectionBundle\JDJCollectionBundle(),
            new JDJ\CMSBundle\JDJCMSBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

    public function init() {
        date_default_timezone_set( 'Europe/Paris' );
        parent::init();
    }

    public function getCacheDir()
    {
        if (in_array($this->environment, array('dev', 'test'))) {
            return '/dev/shm/jdj/cache/' .  $this->environment;
        }

        return parent::getCacheDir();
    }

    public function getLogDir()
    {
        if (in_array($this->environment, array('dev', 'test'))) {
            return '/dev/shm/jdj/logs';
        }

        return parent::getLogDir();
    }
}
