<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 21/08/2014
 * Time: 20:02
 */

namespace JDJ\CollectionBundle\DataFixtures\UserGameAttribute;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadOwnGameData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getDatabaseConnection()
    {
        return $this->container->get('database_connection');
    }


    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $query = <<<EOM
select      old.user_id,
            old.game_id
from        jedisjeux.jdj_ludotheque old
inner join  jdj_jeu jeu on jeu.id = old.game_id
inner join  fos_user user on user.id = old.user_id
limit 1
EOM;

        $oldUserGameAttribute = $this->getDatabaseConnection()->fetchAll($query);
        $userGameAttributeService = $this->container->get("app.service.user.game.attribute");

        $gameManager = $manager->getRepository('JDJJeuBundle:Jeu');
        $userManager = $manager->getRepository('JDJUserBundle:User');

        foreach($oldUserGameAttribute as $data) {

            $jeu = $gameManager->find($data['game_id']);
            $user = $userManager->find($data['user_id']);

            //Call to the usergameattribute service
            $userGameAttributeService->owned($jeu, $user);

            $manager->detach($jeu);
            $manager->detach($user);
            $manager->clear();
        }

    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 5;
    }

} 