<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 04/01/2016
 * Time: 13:51
 */

namespace AppBundle\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\LudographieBundle\Entity\Personne;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadPersonsOfProductsCommand extends ContainerAwareCommand
{
    protected $writeEntityInOutput = false;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:persons-of-products:load')
            ->setDescription('Load persons of products');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        $this->loadAuthorsOfGames();
        $this->loadEditorsOfGames();
        $this->loadIllustratorsOfGames();
    }

    protected function loadAuthorsOfGames()
    {
        $query = <<<EOM
        delete from jdj_designer_product
EOM;
        $this->getDatabaseConnection()->executeQuery($query);

        $query = <<<EOM
insert into jdj_designer_product (
            person_id,
            product_id
)
select      person.id,
  product.id
from        jedisjeux.jdj_personne_game old
  inner join  sylius_product product
    on product.code = concat('game-', old.id_game)
  inner join  jdj_person person
    on person.id = old.id_personne
where       old.type_relation = 'auteur'
EOM;
        $this->getDatabaseConnection()->executeQuery($query);
    }

    protected function loadEditorsOfGames()
    {
        $query = <<<EOM
        delete from jdj_publisher_product
EOM;
        $this->getDatabaseConnection()->executeQuery($query);

        $query = <<<EOM
insert into jdj_publisher_product (
            person_id,
            product_id
)
select      person.id,
  product.id
from        jedisjeux.jdj_personne_game old
  inner join  sylius_product product
    on product.code = concat('game-', old.id_game)
  inner join  jdj_person person
    on person.id = old.id_personne
where       old.type_relation = 'editeur'
EOM;
        $this->getDatabaseConnection()->executeQuery($query);
    }

    protected function loadIllustratorsOfGames()
    {
        $query = <<<EOM
        delete from jdj_artist_product
EOM;
        $this->getDatabaseConnection()->executeQuery($query);

        $query = <<<EOM
insert into jdj_artist_product (
            person_id,
            product_id
)
select      person.id,
  product.id
from        jedisjeux.jdj_personne_game old
  inner join  sylius_product product
    on product.code = concat('game-', old.id_game)
  inner join  jdj_personne person
    on person.id = old.id_personne
where       old.type_relation = 'illustrateur'
EOM;
        $this->getDatabaseConnection()->executeQuery($query);
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    protected function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }
}