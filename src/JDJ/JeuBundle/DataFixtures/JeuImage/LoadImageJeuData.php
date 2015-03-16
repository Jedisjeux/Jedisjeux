<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 28/02/15
 * Time: 17:09
 */

namespace JDJ\JeuBundle\DataFixtures\JeuImage;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JDJ\CoreBundle\Entity\Image;
use JDJ\JeuBundle\Entity\Jeu;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadImageJeuData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
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
                and ie.elem_type = 'jeu'
inner join  jdj_jeu j
                on j.id = ie.elem_id
where not exists (
    select 0
    from   jdj_image i
    where  i.id = old.img_id
)
EOM;

        $this->getDatabaseConnection()->executeQuery($query);

        $this->addImages();
        $this->addCoverImages();
        $this->addMaterialImages();

    }

    private function addCoverImages()
    {
        $query = <<<EOM
update      jdj_jeu jeu
inner join  jedisjeux.jdj_images_elements ie
                on ie.elem_id = jeu.id
                and ie.elem_type = 'jeu'
set         jeu.imageCouverture_id = ie.img_id
where ie.main = 1
EOM;

        $this->getDatabaseConnection()->executeQuery($query);
    }

    private function addMaterialImages()
    {
        $query = <<<EOM
update      jdj_jeu jeu
inner join  jedisjeux.jdj_images_elements ie
                on ie.elem_id = jeu.id
                and ie.elem_type = 'jeu'
set         jeu.materialImage_id = ie.img_id
where ie.ordre = 1
EOM;

        $this->getDatabaseConnection()->executeQuery($query);
    }

    private function addImages()
    {
        $query = <<<EOM
insert into jdj_jeu_image (jeu_id, image_id, description)
select      distinct ie.elem_id, old.img_id, ie.legende
from        jedisjeux.jdj_images old
inner join  jedisjeux.jdj_images_elements ie
                on ie.img_id = old.img_id
                and ie.elem_type = 'jeu'
inner join  jdj_jeu j
                on j.id = ie.elem_id
EOM;

        $this->getDatabaseConnection()->executeQuery($query);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 4;
    }
} 