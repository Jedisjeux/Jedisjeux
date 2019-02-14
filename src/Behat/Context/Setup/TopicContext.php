<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Setup;

use App\Behat\Service\SharedStorageInterface;
use App\Entity\Topic;
use App\Fixture\Factory\ExampleFactoryInterface;
use App\Fixture\Factory\TopicExampleFactory;
use Behat\Behat\Context\Context;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    protected $sharedStorage;

    /**
     * @var TopicExampleFactory
     */
    protected $topicFactory;

    /**
     * @var EntityRepository
     */
    protected $topicRepository;

    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @param SharedStorageInterface $sharedStorage
     * @param TopicExampleFactory    $topicFactory
     * @param EntityRepository       $topicRepository
     * @param ObjectManager          $manager
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        TopicExampleFactory $topicFactory,
        EntityRepository $topicRepository,
        ObjectManager $manager
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->topicFactory = $topicFactory;
        $this->topicRepository = $topicRepository;
        $this->manager = $manager;
    }

    /**
     * @Given there is a topic with title :title written by :customer
     * @Given there is a topic with title :title written by :customer, created at :date
     * @Given there is a topic with title :title written by :customer, created :date
     * @Given I wrote a topic with title :title
     *
     * @param string                 $title
     * @param CustomerInterface|null $customer
     */
    public function thereIsTopicWithTitleWrittenByCustomer($title, CustomerInterface $customer = null, $date = 'now')
    {
        if (null === $customer) {
            $customer = $this->sharedStorage->get('customer');
        }

        /** @var Topic $topic */
        $topic = $this->topicFactory->create([
            'title' => $title,
            'author' => $customer,
            'created_at' => $date,
        ]);

        $this->topicRepository->add($topic);
        $this->sharedStorage->set('topic', $topic);
    }

    /**
     * @Given this topic belongs to :taxon category
     *
     * @param TaxonInterface $taxon
     */
    public function thisTopicBelongsToCategory(TaxonInterface $taxon)
    {
        /** @var Topic $topic */
        $topic = $this->sharedStorage->get('topic');

        $topic->setMainTaxon($taxon);
        $this->manager->flush();
    }
}
