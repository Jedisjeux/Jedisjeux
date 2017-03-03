<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 28/02/15
 * Time: 17:09
 */

namespace AppBundle\Command\Installer\Data;


use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use JDJ\CoreBundle\Entity\Image;
use JDJ\JeuBundle\Entity\Jeu;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadImagesOfPersonsCommand extends ContainerAwareCommand
{
    /**
     * @var ObjectManager
     */
    private $manager;

    protected $output;

    protected function configure()
    {
        $this
            ->setName('app:images-of-persons:load')
            ->setDescription('Loading images of persons')
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $manager */
        $manager = $this->getManager();
        $this->deleteImages();
        $this->load($manager);
    }

    protected function deleteImages()
    {
        $queryBuiler = $this->getManager()->createQuery('delete from AppBundle:PersonImage');
        $queryBuiler->execute();
    }

    /**
     * @return \Doctrine\DBAL\Connection|object
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
insert into jdj_person_image (id, person_id, path, is_main, description)
select      old.img_id, person.id, img_nom, max(ie.main) as is_main, ie.legende as description
from        jedisjeux.jdj_images old
  inner join  jedisjeux.jdj_images_elements ie
    on ie.img_id = old.img_id
       and ie.elem_type = 'personne'
  inner join  jdj_person person
    on person.id = ie.elem_id
group by old.img_id
EOM;

        $this->getDatabaseConnection()->executeQuery($query);
    }

    /**
     * @return EntityManager|object
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
} 