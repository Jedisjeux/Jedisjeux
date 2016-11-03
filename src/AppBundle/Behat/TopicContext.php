<?php

/**
 * Created by PhpStorm.
 * User: loic
 * Date: 23/06/2016
 * Time: 13:48
 */

namespace AppBundle\Behat;

use AppBundle\Entity\Post;
use AppBundle\Entity\Topic;
use Behat\Gherkin\Node\TableNode;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

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
            $mainTaxon = null;
            
            if (isset($data['main_taxon'])) {
                $mainTaxon = $this->getRepository('taxon')->findOneByName($data['main_taxon']);
            }

            $author = isset($data['author']) ? $this->getRepository('customer')->findOneBy(['email' => $data['author']]) : null;

            /** @var Post $mainPost */
            $mainPost = $this->getFactory('post', 'app')->createNew();
            $mainPost->setBody($this->faker->realText());
            $mainPost->setAuthor($author);

            /** @var Topic $topic */
            $topic = $this->getFactory('topic', 'app')->createNew();
            $topic
                ->setMainPost($mainPost)
                ->setTitle(isset($data['title']) ? $data['title'] : $this->faker->title)
                ->setMainTaxon($mainTaxon)
                ->setAuthor($author);

            $manager->persist($topic);
            $manager->flush();
        }
    }
}
