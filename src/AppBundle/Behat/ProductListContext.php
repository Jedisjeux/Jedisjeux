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

use AppBundle\Entity\ProductList;
use Behat\Gherkin\Node\TableNode;
use Sylius\Component\Customer\Model\CustomerInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductListContext extends DefaultContext
{
    /**
     * @Given /^there are product lists:$/
     * @Given /^there are following product lists:$/
     * @Given /^the following product lists exist:$/
     *
     * @param TableNode $table
     */
    public function thereAreProductLists(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            /** @var CustomerInterface $owner */
            $owner = $this->getRepository('customer')->findOneBy(['email' => $data['owner']]);

            /** @var ProductList $productList */
            $productList = $this->getFactory('product_list', 'app')->createNew();
            $productList->setCode(isset($data['code']) ? $data['code'] : $this->faker->postcode);
            $productList->setName(isset($data['name']) ? $data['name'] : $this->faker->jobTitle);
            $productList->setOwner($owner);

            $manager->persist($productList);
        }

        $manager->flush();
    }
}
