<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Frontend\Topic;

class CreateForTaxonPage extends CreatePage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
    {
        return 'app_frontend_topic_create_for_taxon';
    }
}
