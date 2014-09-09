<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 09/09/2014
 * Time: 19:47
 */

namespace JDJ\UserReviewBundle\DataFixtures\Note;

use JDJ\UserReviewBundle\Entity\Note;
use JDJ\WebBundle\DataFixtures\LoadEntityYMLData;

class LoadNotesData extends LoadEntityYMLData
{
    public function getYAMLFileName()
    {
        return __DIR__."/jdj_notes.yml";
    }

    public function getEntityNewInstance()
    {
        return new Note();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }

    public function getTableName()
    {
        return "jdj_note";
    }
} 