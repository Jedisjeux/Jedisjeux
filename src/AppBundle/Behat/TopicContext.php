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

use AppBundle\Entity\Post;
use AppBundle\Entity\Topic;
use AppBundle\Repository\TaxonRepository;
use Behat\Gherkin\Node\TableNode;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicContext extends DefaultContext
{
    /**
     * @Given /^there are topics:$/
     * @Given /^there are following topics:$/
     * @Given /^the following topics exist:$/
     *
     * @param TableNode $table
     */
    public function thereAreTopics(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {

            /** @var TaxonInterface $mainTaxon */
            $mainTaxon = $this->getRepository('taxon')->findOneBySlug($data['main_taxon'], $this->getContainer()->getParameter('locale'));

            if (null === $mainTaxon) {
                throw new \InvalidArgumentException(
                    sprintf('Taxon with permalink "%s" was not found.', $data['main_taxon'])
                );
            }

            /** @var CustomerInterface $author */
            $author = $this->getRepository('customer')->findOneBy(['email' => $data['author']]);

            if (null === $mainTaxon) {
                throw new \InvalidArgumentException(
                    sprintf('Customer with email "%s" was not found.', $data['author'])
                );
            }

            /** @var Post $mainPost */
            $mainPost = $this->getFactory('post', 'app')->createNew();
            $mainPost->setBody($this->faker->realText());
            $mainPost->setAuthor($author);

            /** @var Topic $topic */
            $topic = $this->getFactory('topic', 'app')->createNew();
            $topic
                ->setMainPost($mainPost)
                ->setTitle(isset($data['title']) ? $data['title'] : $this->faker->text(20))
                ->setMainTaxon($mainTaxon)
                ->setAuthor($author);

            $manager->persist($topic);
            $manager->flush();
        }
    }

    /**
     * @Then :topicTitle topic should be categorized under :taxonSlug taxon
     */
    public function topicShouldBeCategorizedUnderTaxon($topicTitle, $taxonSlug)
    {
        /** @var Topic $topic */
        $topic = $this->findOneBy('topic', ['title' => $topicTitle], 'app');

        /** @var TaxonRepository $taxonRepository */
        $taxonRepository = $this->getRepository('taxon');

        $taxon = $taxonRepository->findOneBySlug($taxonSlug, $this->getContainer()->getParameter('locale'));

        if (null === $taxon) {
            throw new \InvalidArgumentException(
                sprintf('Taxon with slug "%s" was not found.', $taxonSlug)
            );
        }

        Assert::notNull($topic->getMainTaxon(), 'topic has no category');
        Assert::eq($taxon->getId(), $topic->getMainTaxon()->getId(), sprintf('topic is not categorized under %s taxon, actual: %s', $taxon->getSlug(), $topic->getMainTaxon()->getSlug()));
    }
}

