<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 21/09/2014
 * Time: 21:57
 */

namespace JDJ\UserReviewBundle\Behat;


use Behat\Gherkin\Node\TableNode;
use JDJ\CoreBundle\Behat\DefaultContext;
use JDJ\UserReviewBundle\Entity\JeuNote;
use JDJ\UserReviewBundle\Entity\UserReview;

class UserReviewContext extends DefaultContext
{
    /**
     * @Given /^game "([^""]*)" has following user reviews:$/
     */
    public function thereAreUsers($jeuLibelle, TableNode $table){
        $manager = $this->getEntityManager();

        $jeu = $this->findOneBy("jeu", array(
            'libelle' => $jeuLibelle,
        ));

        foreach ($table->getHash() as $data) {

            $user = $this->findOneBy("user", array(
                "username" => $data['username']
            ));

            $note = $this->findOneBy("note", array(
                "valeur" => $data['note']
            ));

            $jeuNote = $this->getRepository("jeuNote")->findOneBy(array(
                'author' => $user,
                'jeu' => $jeu,
            ));

            if (null === $jeuNote) {
                $jeuNote = new JeuNote();
                $jeuNote
                    ->setAuthor($user)
                    ->setJeu($jeu)
                    ->setNote($note)
                ;

                $manager->persist($note);
            }

            $userReview = new UserReview();
            $userReview
                ->setBody($data['body'])
                ->setJeuNote($jeuNote)
                ->setLibelle($data['libelle'])
            ;

            $manager->persist($userReview);

        }

        $manager->flush();

    }
} 