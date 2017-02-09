<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat;

use AppBundle\Document\ArticleContent;
use AppBundle\Entity\Article;
use Behat\Gherkin\Node\TableNode;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleContext extends DefaultContext
{
    /**
     * @Given /^there are articles:$/
     * @Given /^there are following articles:$/
     * @Given /^the following articles exist:$/
     *
     * @param TableNode $table
     */
    public function thereAreArticles(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            /** @var TaxonInterface $taxon */
            $taxon = $this->getRepository('taxon')->findOneByPermalink($data['taxon']);

            if (null === $taxon) {
                throw new \InvalidArgumentException(
                    sprintf('Taxon with permalink "%s" was not found.', $data['taxon'])
                );
            }

            /** @var Article $article */
            $article = $this->getFactory('article', 'app')->createNew();
            $article
                ->setCode(isset($data['code']) ? $data['code'] : $this->faker->postcode)
                ->setStatus(Article::STATUS_PUBLISHED)
                ->setMainTaxon($taxon);

            /** @var ArticleContent $articleContent */
            $articleContent = $article->getDocument();
            $articleContent
                ->setName(isset($data['name']) ? $data['name'] : $this->faker->slug())
                ->setTitle(isset($data['title']) ? $data['title'] : $this->faker->text(20))
                ->setPublishable(true);

            $manager->persist($article);
        }

        $manager->flush();
    }
}