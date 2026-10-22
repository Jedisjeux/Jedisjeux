<?php

namespace App;

use App\DependencyInjection\Compiler\ServicesPass;
use Doctrine\Inflector\InflectorFactory;
use Doctrine\Inflector\Rules\Patterns;
use Doctrine\Inflector\Rules\Ruleset;
use Doctrine\Inflector\Rules\Substitution;
use Doctrine\Inflector\Rules\Substitutions;
use Doctrine\Inflector\Rules\Transformations;
use Doctrine\Inflector\Rules\Word;
use Sylius\Component\Resource\Metadata\Metadata;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        // Keep "taxons" as plural name of the taxon resource, doctrine/inflector 2 pluralizes it as "taxa"
        Metadata::setInflector(InflectorFactory::create()
            ->withPluralRules(new Ruleset(
                new Transformations(),
                new Patterns(),
                new Substitutions(new Substitution(new Word('taxon'), new Word('taxons')))
            ))
            ->build());

        parent::boot();
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        return $this->getProjectDir().'/var/cache/'.$this->environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        return $this->getProjectDir().'/var/log';
    }

    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $contents = require $this->getProjectDir().'/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if (isset($envs['all']) || isset($envs[$this->environment])) {
                yield new $class();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new ServicesPass());
    }

    /**
     * {@inheritdoc}
     */
    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        $container->addResource(new FileResource($this->getProjectDir().'/config/bundles.php'));
        // Feel free to remove the "container.autowiring.strict_mode" parameter
        // if you are using symfony/dependency-injection 4.0+ as it's the default bbundlesehavior
        $container->setParameter('container.autowiring.strict_mode', true);
        $container->setParameter('container.dumper.inline_class_loader', true);
        $confDir = $this->getProjectDir().'/config';

        $loader->load($confDir.'/{packages}/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{packages}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}_'.$this->environment.self::CONFIG_EXTS, 'glob');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $confDir = $this->getProjectDir().'/config';

        $routes->import($confDir.'/{routes}/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}'.self::CONFIG_EXTS, '/', 'glob');
    }
}
