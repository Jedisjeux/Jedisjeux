<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 03/03/15
 * Time: 19:18
 */

namespace JDJ\PartieBundle\DataFixtures;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JDJ\CoreBundle\Entity\Image;
use JDJ\JeuBundle\Entity\Jeu;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadImagePartieData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
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
insert into jdj_image (id, path)
select      distinct old.img_id, img_nom
from        jedisjeux.jdj_images old
inner join  jedisjeux.jdj_images_elements ie
                on ie.img_id = old.img_id
                and ie.elem_type = 'partie'
inner join  jdj_partie partie
                on partie.id = ie.elem_id
where not exists (
    select 0
    from   jdj_image i
    where  i.id = old.img_id
)
EOM;

        $this->getDatabaseConnection()->executeQuery($query);

        $this->addImages();

    }

    private function addImages()
    {
        $query = <<<EOM
insert into jdj_partie_image (partie_id, image_id)
select      distinct ie.elem_id, old.img_id
from        jedisjeux.jdj_images old
inner join  jedisjeux.jdj_images_elements ie
                on ie.img_id = old.img_id
                and ie.elem_type = 'partie'
inner join  jdj_partie partie
                on partie.id = ie.elem_id
EOM;

        $this->getDatabaseConnection()->executeQuery($query);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 5;
    }
} 