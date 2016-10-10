<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat;

use AppBundle\Entity\GamePlay;
use AppBundle\Factory\GamePlayFactory;
use AppBundle\Repository\ProductRepository;
use Behat\Gherkin\Node\TableNode;
use Sylius\Component\User\Model\CustomerInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class GamePlayContext extends DefaultContext
{
    /**
     * @Given /^there are game plays:$/
     * @Given /^there are following game plays:$/
     * @Given /^the following game plays exist:$/
     *
     * @param TableNode $table
     */
    public function thereAreGamePlays(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {

            /** @var ProductRepository $productRepository */
            $productRepository = $this->getRepository('product');
            $product = $productRepository->findOneByName($data['product']);

            /** @var CustomerInterface $author */
            $author = $this->getRepository('customer')->findOneBy(['email' => $data['author']]);

            /** @var GamePlayFactory $gamePlayFactory */
            $gamePlayFactory = $this->getFactory('game_play', 'app');

            /** @var GamePlay $gamePlay */
            $gamePlay = $gamePlayFactory->createForProduct($product->getSlug());
            $gamePlay->setAuthor($author);

            $manager->persist($gamePlay);
        }

        $manager->flush();
    }
}
