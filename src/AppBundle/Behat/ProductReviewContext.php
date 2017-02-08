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

use AppBundle\Entity\ProductReview;
use AppBundle\Repository\ProductRepository;
use Behat\Gherkin\Node\TableNode;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductReviewContext extends DefaultContext
{
    /**
     * @Given /^there are product reviews:$/
     * @Given /^there are following product reviews:$/
     * @Given /^the following product reviews exist:$/
     *
     * @param TableNode $table
     */
    public function thereAreProductReviews(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            /** @var CustomerInterface $author */
            $author = $this->getRepository('customer')->findOneBy(['email' => $data['author']]);

            /** @var ProductRepository $productRepository */
            $productRepository = $this->getRepository('product');
            $product = $productRepository->findByName($data['product'], $this->getContainer()->getParameter('locale'))[0];

            /** @var ProductReview $productReview */
            $productReview = $this->getFactory('productReview', 'sylius')->createNew();
            $productReview->setTitle(isset($data['title']) ? $data['title'] : $this->faker->jobTitle);
            $productReview->setComment(isset($data['comment']) ? $data['comment'] : $this->faker->realText());
            $productReview->setReviewSubject($product);
            $productReview->setAuthor($author);
            $productReview->setRating(isset($data['rating']) ? (int)$data['rating'] : $this->faker->numberBetween(1, 10));

            $manager->persist($productReview);
        }

        $manager->flush();
    }
}