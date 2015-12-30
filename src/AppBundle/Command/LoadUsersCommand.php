<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 30/12/2015
 * Time: 15:59
 */

namespace AppBundle\Command;

use FOS\UserBundle\Model\UserManagerInterface;
use JDJ\UserBundle\Entity\User;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class LoadUsersCommand extends LoadCommand
{
    protected $writeEntityInOutput = false;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:users:load')
            ->setDescription('Load users');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $output->writeln("<comment>Load users</comment>");

        parent::execute($input, $output);
    }

    /**
     * @inheritdoc
     */
    public function getRows()
    {
        $query = <<<EOM
select      old.user_id as id,
            old.username as username,
            old.user_email as email,
            old.user_avatar,
            old.group_id
from        jedisjeux.phpbb3_users old
where       old.user_email != ''
group by    old.username_clean
order by    old.user_id asc
EOM;
        $rows = $this->getDatabaseConnection()->fetchAll($query);

        return $rows;
    }

    /**
     * @inheritdoc
     */
    public function postSetData($entity)
    {
        $roles = array('ROLE_USER');
        switch($entity->getUsername()) {
            case 'loic_425':
                $roles[] = 'ROLE_ADMIN';
                break;
            case 'jedisjeux':
                $roles[] = 'ROLE_ADMIN';
                break;
        }

        if (null === $entity->getId()) {
            $entity
                ->setPlainPassword(md5(uniqid($entity->getUsername(), true)));
        }

        $this->getManager()->updateCanonicalFields($entity);
        $this->getManager()->updatePassword($entity);
    }

    public function createEntityNewInstance()
    {
        return $this->getManager()->createUser();
    }

    public function getTableName()
    {
        return 'fos_user';
    }

    public function getRepository()
    {
        return $this->getEntityManager()->getRepository('JDJUserBundle:User');
    }

    /**
     * @return UserManagerInterface
     */
    public function getManager()
    {
        return $this->getContainer()->get('fos_user.user_manager');
    }
}