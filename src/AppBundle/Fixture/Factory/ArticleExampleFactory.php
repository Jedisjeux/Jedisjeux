<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Fixture\Factory;

use AppBundle\Entity\Article;
use AppBundle\Entity\ArticleImage;
use AppBundle\Entity\Block;
use AppBundle\Fixture\OptionsResolver\LazyOption;
use AppBundle\Formatter\StringInflector;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $articleFactory;

    /**
     * @var FactoryInterface
     */
    private $articleImageFactory;

    /**
     * @var RepositoryInterface
     */
    private $customerRepository;

    /**
     * @var TaxonRepositoryInterface
     */
    private $taxonRepository;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @param FactoryInterface $articleFactory
     * @param FactoryInterface $articleImageFactory
     * @param RepositoryInterface $customerRepository
     * @param TaxonRepositoryInterface $taxonRepository
     */
    public function __construct(
        FactoryInterface $articleFactory,
        FactoryInterface $articleImageFactory,
        RepositoryInterface $customerRepository,
        TaxonRepositoryInterface $taxonRepository
    )
    {
        $this->articleFactory = $articleFactory;
        $this->articleImageFactory = $articleImageFactory;
        $this->customerRepository = $customerRepository;
        $this->taxonRepository = $taxonRepository;

        $this->faker = \Faker\Factory::create('fr_FR');
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var Article $article */
        $article = $this->articleFactory->createNew();
        $article->setCode($options['code']);
        $article->setTitle($options['title']);
        $article->setShortDescription($options['short_description']);
        $article->setPublishStartDate($options['publish_start_date']);
        $article->setStatus($options['status']);
        $article->setAuthor($options['author']);
        $article->setMainTaxon($options['main_taxon']);

        $this->createImage($article, $options);

        return $article;
    }

    /**
     * @param Article $article
     * @param array $options
     */
    private function createImage(Article $article, array $options)
    {
        $imagePath = $options['main_image'];
        /** @var ArticleImage $articleImage */
        $articleImage = $this->articleImageFactory->createNew();
        $articleImage->setPath(basename($imagePath));

        file_put_contents($articleImage->getAbsolutePath(), file_get_contents($imagePath));

        $article->setMainImage($articleImage);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('title', function (Options $options) {
                return ucfirst($this->faker->words(3, true));
            })

            ->setDefault('code', function (Options $options) {
                return StringInflector::nameToCode($options['title']);
            })

            ->setDefault('short_description', function (Options $options) {
                return "<p>" . implode("</p><p>", $this->faker->paragraphs(2)) . '</p>';
            })

            ->setDefault('publish_start_date', function (Options $options) {
                return $this->faker->dateTimeBetween('2 months ago', 'yesterday');
            })
            ->setNormalizer('publish_start_date', function (Options $options, $createdAt) {
                if (!is_string($createdAt)) {
                    return $createdAt;
                }

                return new \DateTime($createdAt);
            })

            ->setDefault('status', function (Options $options) {
                return $this->faker->randomElement([
                    Article::STATUS_NEW,
                    Article::STATUS_PENDING_REVIEW,
                    Article::STATUS_PENDING_PUBLICATION,
                    Article::STATUS_PUBLISHED]);
            })

            ->setDefault('main_image', function (Options $options) {
                return $this->faker->image();
            })

            ->setDefault('author', LazyOption::randomOne($this->customerRepository))
            ->setAllowedTypes('author', ['null', 'string', CustomerInterface::class])
            ->setNormalizer('author', LazyOption::findOneBy($this->customerRepository, 'email'))

            ->setDefault('main_taxon', LazyOption::randomOne($this->taxonRepository))
            ->setAllowedTypes('main_taxon', ['null', 'string', TaxonInterface::class])
            ->setNormalizer('main_taxon', LazyOption::findOneBy($this->taxonRepository, 'code'));
    }
}
