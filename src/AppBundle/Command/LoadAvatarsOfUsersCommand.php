<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 12/02/2016
 * Time: 13:40
 */

namespace AppBundle\Command;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadAvatarsOfUsersCommand extends ContainerAwareCommand
{
    /**
     * @var ObjectManager
     */
    private $manager;

    protected $output;

    protected function configure()
    {
        $this
            ->setName('app:avatars-of-users:load')
            ->setDescription('Loading avatars of users');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $manager */
        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->load($manager);
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $query = <<<EOM
insert into jdj_customer_avatar (id, path)
select      distinct old.user_id, user_avatar
from        jedisjeux.phpbb3_users old
where not exists (
    select 0
    from   jdj_customer_avatar i
    where  i.id = old.user_id
)
and old.user_avatar <> ''
EOM;

        $this->getDatabaseConnection()->executeQuery($query);

        $query = <<<EOM
update   fos_user user
inner join jdj_customer_avatar avatar on avatar.id = user.id
    set user.avatar_id = avatar.id
EOM;

        $this->getDatabaseConnection()->executeQuery($query);
    }
}
