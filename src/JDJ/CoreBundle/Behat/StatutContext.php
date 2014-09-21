<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 18/09/2014
 * Time: 22:35
 */

namespace JDJ\CoreBundle\Behat;



use JDJ\WebBundle\Entity\Statut;

class StatutContext extends DefaultContext
{
    /**
     * @Given /^there are default status:$/
     */
    public function thereAreDefaultStatus(){
        $manager = $this->getEntityManager();

        $statuts = array(
            'validé',
            'à relire',
        );

        foreach($statuts as $statutLibelle) {
            /** @var Statut $statut */
            $statut = new Statut();
            $statut
                ->setLibelle(trim($statutLibelle))
            ;

            $manager->persist($statut);
        }

        $manager->flush();
    }
} 