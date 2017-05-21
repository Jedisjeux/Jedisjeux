<?php

/*
 * This file is part of jdj project.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat;

use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class NotificationContext extends DefaultContext
{
    /**
     * @Then there is a notification to :customerEmail for :productName product
     *
     * @param string $customerEmail
     * @param string $productName
     */
    public function thereIsNotificationToCustomerForProduct($customerEmail, $productName)
    {
        $recipient = $this->findOneBy('customer', ['email' => $customerEmail]);
        $product = $this->getRepository('product')->findByName($productName, $this->getContainer()->getParameter('locale'));

        $notification = $this->getRepository('notification', 'app')->findOneBy([
            'recipient' => $recipient,
            'product' => $product,
        ]);

        Assert::notNull($notification, 'No notification found');
    }

    /**
     * @Then there is a notification to :customerEmail for :articleTitle article
     *
     * @param string $customerEmail
     * @param string $articleTitle
     */
    public function thereIsNotificationToCustomerForArticle($customerEmail, $articleTitle)
    {
        $recipient = $this->findOneBy('customer', ['email' => $customerEmail]);
        $article = $this->findOneBy('article', ['title' => $articleTitle], 'app');

        $notification = $this->getRepository('notification', 'app')->findOneBy([
            'recipient' => $recipient,
            'article' => $article,
        ]);

        Assert::notNull($notification, 'No notification found');
    }
}
