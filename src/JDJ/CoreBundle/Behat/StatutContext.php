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
     * @Given /^there are default status$/
     */
    public function thereAreDefaultStatus(){
        $manager = $this->getEntityManager();

        $statuts = array(
            Statut::PUBLISHED => 'publié',
            Statut::INCOMPLETE => 'à compléter',
            Statut::NEED_A_READ => 'à relire',
        );

        foreach($statuts as $code => $libelle) {
            /** @var Statut $statut */
            $statut = new Statut();
            $statut
                ->setCode($code)
                ->setLibelle(trim($libelle))
            ;

            $manager->persist($statut);
        }

        $manager->flush();
    }
} 