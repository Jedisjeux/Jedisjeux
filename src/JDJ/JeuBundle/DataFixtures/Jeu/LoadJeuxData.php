<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 03/09/2014
 * Time: 20:00
 */

namespace JDJ\JeuBundle\DataFixtures\Jeu;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JDJ\JeuBundle\Entity\Jeu;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadJeuxData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{

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
        $query = <<<EOM
select      old.*
from        old_jedisjeux.jdj_game old
inner join  jdj_statut statut
                on statut.id = old.valid
limit       500
EOM;


        $oldJeux = $this->getDatabaseConnection()->fetchAll($query);

        foreach($oldJeux as $data)
        {
            $this->getDatabaseConnection()->insert("jdj_jeu", array(
                'id' => $data['id'],
                'libelle' => $data['nom'],
                'imageCouverture' => (!empty($data['couverture']) ? $data['couverture'] : null),
                'joueurMin' => (!empty($data['min']) ? $data['min'] : null),
                'joueurMax' => (!empty($data['max']) ? $data['max'] : null),
                // TODO Durée -setDuree(empty($data['max']) ? $data['max'] : null)
                'description' => (!empty($data['presentation']) ? $this->getHTMLFromText($data['presentation']) : null),
                'intro' => (!empty($data['intro']) ? $this->getHTMLFromText($data['intro']) : null),
                'materiel' => (!empty($data['materiel']) ? $data['materiel'] : null),
                'but' => (!empty($data['but']) ? $this->getHTMLFromText($data['but']) : null),
                // TODO date de création
                'ageMin' => (!empty($data['age_min']) ? $data['age_min'] : null),
                'slug' => str_replace(' ', '-', $data['mot_cle']),
                'statut_id' => $data['valid']
            ));
        }
    }

    public function getEntityFromData(array $data)
    {
        $jeu = new Jeu();
        $jeu
            ->setLibelle($data['nom'])
            ->setImageCouverture(!empty($data['couverture']) ? $data['couverture'] : null)
            ->setJoueurMin(!empty($data['min']) ? $data['min'] : null)
            ->setJoueurMax(!empty($data['max']) ? $data['max'] : null)
            // TODO Durée -setDuree(empty($data['max']) ? $data['max'] : null)
            ->setDescription(!empty($data['presentation']) ? $this->getHTMLFromText($data['presentation']) : null)
            ->setIntro(!empty($data['intro']) ? $this->getHTMLFromText($data['intro']) : null)
            ->setMateriel(!empty($data['materiel']) ? $data['materiel'] : null)
            ->setBut(!empty($data['but']) ? $this->getHTMLFromText($data['but']) : null)
            // TODO date de création
            ->setAgeMin(!empty($data['age_min']) ? $data['age_min'] : null)
            // TODO author (user)y

            // TODO date de sortie
        ;

        return $jeu;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 3;
    }

    private function getHTMLFromText($text)
    {
        $text = trim($text);

        /**
         * Turn Double carryage returns into <p>
         */
        $text = "<p>".preg_replace("/\\n(\\r)?\n/", "</p><p>", $text)."</p>";

        /**
         * Turn Simple carryage returns into <br />
         */
        $text = "<p>".preg_replace("/\\n/", "<br />", $text)."</p>";

        $text = $this->cleanBBCode($text);

        $text = preg_replace("/\<(b|strong)\>/", '</p><h4>', $text);
        $text = preg_replace("/\<\/(b|strong)\>/", '</h4><p>', $text);

        $text = preg_replace("/\<p\>( |\n|\r)*\<br \/>/", '<p>', $text);
        $text = preg_replace("/\<p\>( )*\<\/p\>/", '', $text);
        return $text;
    }

    private function cleanBBCode($text)
    {
        $text = preg_replace("/\[(b):[0-9a-z]+\]/", '</p><h4>', $text);
        $text = preg_replace("/\[(\/)?(b):[0-9a-z]+\]/", '</h4><p>', $text);
        $text = preg_replace("/\[(\/)?(i):[0-9a-z]+\]/", '<${1}i>', $text);
        return $text;
    }
} 