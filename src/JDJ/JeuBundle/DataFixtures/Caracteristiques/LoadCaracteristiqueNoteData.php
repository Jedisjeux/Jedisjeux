<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 09/09/2014
 * Time: 19:54
 */

namespace JDJ\JeuBundle\DataFixtures\Caracteristiques;


use Doctrine\ORM\EntityRepository;
use JDJ\JeuBundle\Entity\CaracteristiqueNote;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadCaracteristiqueNoteData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{
    private $manager;

    /**
     * @return ObjectManager
     */
    public function getManager()
    {
        return $this->manager;
    }

   /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $this->loadComplexite();
        $this->loadHasard();
        $this->loadStrategie();
        $this->loadInteractivite();

        $manager->flush();
    }

    /**
     * Load all Complexite notes
     */
    private function loadComplexite()
    {
        $caracteristique = $this->createNew();
        $caracteristique
            ->setValeur(1)
            ->setLibelle("très simple")
            ->setCaracteristique($this->getCaracteristiqueRepository()->find(1));
        ;
        $this->getManager()->persist($caracteristique);


        $caracteristique = $this->createNew();
        $caracteristique
            ->setValeur(2)
            ->setLibelle("simple")
            ->setCaracteristique($this->getCaracteristiqueRepository()->find(1));
        ;
        $this->getManager()->persist($caracteristique);


        $caracteristique = $this->createNew();
        $caracteristique
            ->setValeur(3)
            ->setLibelle("moyenne")
            ->setCaracteristique($this->getCaracteristiqueRepository()->find(1));
        ;
        $this->getManager()->persist($caracteristique);


        $caracteristique = $this->createNew();
        $caracteristique
            ->setValeur(4)
            ->setLibelle("complexe")
            ->setCaracteristique($this->getCaracteristiqueRepository()->find(1));
        ;
        $this->getManager()->persist($caracteristique);


        $caracteristique = $this->createNew();
        $caracteristique
            ->setValeur(5)
            ->setLibelle("très complexe")
            ->setCaracteristique($this->getCaracteristiqueRepository()->find(1));
        ;
        $this->getManager()->persist($caracteristique);
    }

    /**
     * Load all Hasard notes
     */
    public function loadHasard()
    {
        $caracteristique = $this->createNew();
        $caracteristique
            ->setValeur(1)
            ->setLibelle("aucun hasard")
            ->setCaracteristique($this->getCaracteristiqueRepository()->find(2));
        ;
        $this->getManager()->persist($caracteristique);


        $caracteristique = $this->createNew();
        $caracteristique
            ->setValeur(2)
            ->setLibelle("peu de hasard")
            ->setCaracteristique($this->getCaracteristiqueRepository()->find(2));
        ;
        $this->getManager()->persist($caracteristique);


        $caracteristique = $this->createNew();
        $caracteristique
            ->setValeur(3)
            ->setLibelle("pas mal de hasard")
            ->setCaracteristique($this->getCaracteristiqueRepository()->find(2));
        ;
        $this->getManager()->persist($caracteristique);


        $caracteristique = $this->createNew();
        $caracteristique
            ->setValeur(4)
            ->setLibelle("beaucoup de hasard")
            ->setCaracteristique($this->getCaracteristiqueRepository()->find(2));
        ;
        $this->getManager()->persist($caracteristique);


        $caracteristique = $this->createNew();
        $caracteristique
            ->setValeur(5)
            ->setLibelle("on ne contrôle rien")
            ->setCaracteristique($this->getCaracteristiqueRepository()->find(2));
        ;
        $this->getManager()->persist($caracteristique);
    }

    /**
     * Load all Strategie notes
     */
    public function loadStrategie()
    {
        $caracteristique = $this->createNew();
        $caracteristique
            ->setValeur(1)
            ->setLibelle("aucune stratégie")
            ->setCaracteristique($this->getCaracteristiqueRepository()->find(3));
        ;
        $this->getManager()->persist($caracteristique);


        $caracteristique = $this->createNew();
        $caracteristique
            ->setValeur(2)
            ->setLibelle("peu de stratégie")
            ->setCaracteristique($this->getCaracteristiqueRepository()->find(3));
        ;
        $this->getManager()->persist($caracteristique);


        $caracteristique = $this->createNew();
        $caracteristique
            ->setValeur(3)
            ->setLibelle("pas mal de stratégie")
            ->setCaracteristique($this->getCaracteristiqueRepository()->find(3));
        ;
        $this->getManager()->persist($caracteristique);


        $caracteristique = $this->createNew();
        $caracteristique
            ->setValeur(4)
            ->setLibelle("beaucoup de stratégie")
            ->setCaracteristique($this->getCaracteristiqueRepository()->find(3));
        ;
        $this->getManager()->persist($caracteristique);


        $caracteristique = $this->createNew();
        $caracteristique
            ->setValeur(5)
            ->setLibelle("on peut tout calculer")
            ->setCaracteristique($this->getCaracteristiqueRepository()->find(3));
        ;
        $this->getManager()->persist($caracteristique);
    }


    /**
     * Load all Interactivite notes
     */
    public function loadInteractivite()
    {
        $caracteristique = $this->createNew();
        $caracteristique
            ->setValeur(1)
            ->setLibelle("aucun interactivité")
            ->setCaracteristique($this->getCaracteristiqueRepository()->find(4));
        ;
        $this->getManager()->persist($caracteristique);


        $caracteristique = $this->createNew();
        $caracteristique
            ->setValeur(2)
            ->setLibelle("peu d'interactivité")
            ->setCaracteristique($this->getCaracteristiqueRepository()->find(4));
        ;
        $this->getManager()->persist($caracteristique);


        $caracteristique = $this->createNew();
        $caracteristique
            ->setValeur(3)
            ->setLibelle("pas mal d'interactivité")
            ->setCaracteristique($this->getCaracteristiqueRepository()->find(4));
        ;
        $this->getManager()->persist($caracteristique);


        $caracteristique = $this->createNew();
        $caracteristique
            ->setValeur(4)
            ->setLibelle("beaucoup d'interactivité")
            ->setCaracteristique($this->getCaracteristiqueRepository()->find(4));
        ;
        $this->getManager()->persist($caracteristique);


        $caracteristique = $this->createNew();
        $caracteristique
            ->setValeur(5)
            ->setLibelle("énormément d'interactivité")
            ->setCaracteristique($this->getCaracteristiqueRepository()->find(4));
        ;
        $this->getManager()->persist($caracteristique);
    }


    public function createNew() {
        return new CaracteristiqueNote();
    }

    /**
     * @return EntityRepository
     */
    public function getCaracteristiqueRepository()
    {
        return $this->getManager()->getRepository('JDJJeuBundle:Caracteristique');
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 2;
    }

} 