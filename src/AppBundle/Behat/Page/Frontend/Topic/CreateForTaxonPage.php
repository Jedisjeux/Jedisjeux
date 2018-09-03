<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Page\Frontend\Topic;

use AppBundle\Behat\Page\SymfonyPage;
use Behat\Mink\Exception\ElementNotFoundException;

class CreateForTaxonPage extends CreatePage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName()
    {
        return 'app_frontend_topic_create_for_taxon';
    }
}