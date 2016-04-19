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

            new Sylius\Bundle\InstallerBundle\SyliusInstallerBundle(),
            new Sylius\Bundle\ArchetypeBundle\SyliusArchetypeBundle(),
            new Sylius\Bundle\ProductBundle\SyliusProductBundle(),
            new Sylius\Bundle\AssociationBundle\SyliusAssociationBundle(),
            new Sylius\Bundle\UserBundle\SyliusUserBundle(),
            new Sylius\Bundle\MailerBundle\SyliusMailerBundle(),
            new Sylius\Bundle\ReviewBundle\SyliusReviewBundle(),
            new Sylius\Bundle\ResourceBundle\SyliusResourceBundle(),
            new Sylius\Bundle\AttributeBundle\SyliusAttributeBundle(),
            new Sylius\Bundle\VariationBundle\SyliusVariationBundle(),
            new Sylius\Bundle\TaxonomyBundle\SyliusTaxonomyBundle(),
            new Sylius\Bundle\TranslationBundle\SyliusTranslationBundle(),

            new Sonata\BlockBundle\SonataBlockBundle(),

            new Symfony\Cmf\Bundle\CoreBundle\CmfCoreBundle(),
            new Symfony\Cmf\Bundle\BlockBundle\CmfBlockBundle(),
            new Symfony\Cmf\Bundle\ContentBundle\CmfContentBundle(),
            new Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle(),
            new Symfony\Cmf\Bundle\MenuBundle\CmfMenuBundle(),
            new Symfony\Cmf\Bundle\CreateBundle\CmfCreateBundle(),
            new Symfony\Cmf\Bundle\MediaBundle\CmfMediaBundle(),
            new Sylius\Bundle\ContentBundle\SyliusContentBundle(),

            new Doctrine\Bundle\PHPCRBundle\DoctrinePHPCRBundle(),
            new Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),

            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Sonata\IntlBundle\SonataIntlBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new FOS\CommentBundle\FOSCommentBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle($this),
            new \Liip\ImagineBundle\LiipImagineBundle(),
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new Gregwar\FormBundle\GregwarFormBundle(),
            new FOS\ElasticaBundle\FOSElasticaBundle(),
            //new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            //new Sonata\CoreBundle\SonataCoreBundle(),
            //new Sonata\BlockBundle\SonataBlockBundle(),
            new Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle(),
            new Craue\FormFlowBundle\CraueFormFlowBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new JMS\TranslationBundle\JMSTranslationBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new Bmatzner\FontAwesomeBundle\BmatznerFontAwesomeBundle(),
            new JMS\AopBundle\JMSAopBundle(),

            new Ob\HighchartsBundle\ObHighchartsBundle(),
            new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),
            new Knp\DoctrineBehaviors\Bundle\DoctrineBehaviorsBundle(),
            new Bmatzner\JQueryUIBundle\BmatznerJQueryUIBundle(),
            new Bmatzner\JQueryBundle\BmatznerJQueryBundle(),
            new FM\BbcodeBundle\FMBbcodeBundle(),

            //JDJ
            // TODO there can be only one !
            new JDJ\ComptaBundle\JDJComptaBundle(),
            new JDJ\JeuBundle\JDJJeuBundle(),
            new JDJ\UserBundle\JDJUserBundle(),
            new JDJ\UserReviewBundle\JDJUserReviewBundle(),
            new JDJ\CoreBundle\JDJCoreBundle(),
            new JDJ\SearchBundle\JDJSearchBundle(),
            new JDJ\CollectionBundle\JDJCollectionBundle(),
            new JDJ\CMSBundle\JDJCMSBundle(),
            new JDJ\JediZoneBundle\JDJJediZoneBundle(),
            new AppBundle\AppBundle(),
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
}
