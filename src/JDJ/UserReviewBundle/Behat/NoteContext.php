<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 21/09/2014
 * Time: 22:40
 */

namespace JDJ\UserReviewBundle\Behat;


use JDJ\CoreBundle\Behat\DefaultContext;
use JDJ\UserReviewBundle\Entity\Note;

class NoteContext extends DefaultContext
{

    /**
     * @Given /^there are default notes$/
     */
    public function thereAreDefaultNotes(){
        $manager = $this->getEntityManager();

        $notes = array(
            1 => '1/10 : nul',
            2 => '2/10 : nul',
            3 => '3/10 : nul',
            4 => '4/10 : nul',
            5 => '5/10 : Bof',
            6 => '6/10 : Moyen',
            7 => '7/10 : Bon',
            9 => '8/10 : Très bon',
            9 => '9/10 : Excellent',
            10 => '10/10 : Mythique',
        );

        foreach($notes as $valeur => $libelle) {
            $note = new Note();
            $note
                ->setLibelle($libelle)
                ->setValeur($valeur)
            ;
            $manager->persist($note);
        }

        $manager->flush();
    }
} 