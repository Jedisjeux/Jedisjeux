<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 17/01/16
 * Time: 17:32
 */

namespace AppBundle\Command;

use Doctrine\ODM\PHPCR\Document\Generic;
use Doctrine\ODM\PHPCR\DocumentManager;
use Sylius\Bundle\ContentBundle\Doctrine\ODM\PHPCR\StaticContentRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Cmf\Bundle\ContentBundle\Doctrine\Phpcr\StaticContent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LoadPagesCommand extends ContainerAwareCommand
{
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:pages:load')
            ->setDescription('Load pages');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $output->writeln("<comment>Load pages</comment>");

        foreach ($this->getPages() as $data) {
            $page = $this->createOrReplacePage($data);
            $this->getManager()->persist($page);
            $this->getManager()->flush();
        }
    }

    /**
     * @param array $data
     * @return StaticContent
     */
    protected function createOrReplacePage(array $data)
    {
        $page = $this->findPage($data['name']);

        if (null === $page) {
            $page = new StaticContent();
            $page
                ->setParentDocument($this->getParent());
        }

        $page->setName($data['name']);
        $page->setTitle($data['title']);
        $page->setBody($data['body']);
        $page->setPublishable(false);

        return $page;
    }

    /**
     * @param string $id
     * @return StaticContent
     */
    protected function findPage($id)
    {
        $page = $this
            ->getRepository()
            ->findStaticContent($id);

        return $page;
    }

    /**
     * @return Generic
     */
    protected function getParent()
    {
        return $this->getManager()->find(null, '/cms/pages');
    }

    /**
     * @return StaticContentRepository
     */
    public function getRepository()
    {
        return $this->getContainer()->get('sylius.repository.static_content');
    }

    /**
     * @return DocumentManager
     */
    public function getManager()
    {
        return $this->getContainer()->get('sylius.manager.static_content');
    }

    protected function getPages()
    {
        return array(
            array(
                'name' => 'mentions-legales',
                'title' => 'Mentions légales',
                'body' => '
<h3>Fiche d\'identitité de l\'association Jedisjeux</h3>
<p>
    <strong>Date de la déclaration :</strong>
    17 mars 2011
</p>
<p>
    <strong>N° de parution :</strong>
    N° d\'annonce 750 parue le 2 avril 2011
</p>
<p>
    <strong>Association déclarée à :</strong>
    La préfecture de Rennes (35)
</p>


    <h4>Siège social :</h4>
    <address><JEDISJEUX<br>
    Fremont Loïc<br>
    2 allée de la châtaigneraie<br>
    35 740 PACE
    </address>

<p>
    <strong>SIREN :</strong>
    537 646 036 /
    <strong>SIRET :</strong>
    537 646 036 00013 /
    <strong>Code APE :</strong>
    9499Z
</p>
<p>
    <strong>Objet :</strong>
    Cette association a pour objet :<br>

    - fournir des informations sur les jeux de société par le biais de leur site Internet « Jedisjeux.net ».<br>
    - de regrouper des passionnés œuvrant au développement de l’activité ludique.<br>
    - de promouvoir et de participer à des événementiels autour du jeu de société.<br>
    - de diffuser toutes informations qu’elle jugera souhaitable par des moyens appropriés.<br>
    - de proposer des stages de formation, de découverte et d’initiation aux particuliers et aux professionnels.<br>
    - de proposer des ateliers de création de jeux de société.<br><br>

    L’association pourra également, de façon habituelle, exercer une activité économique de prestation de service d’animations ludiques
    et procéder à la location de jeux.
</p>
<p>
    <strong>Statuts de l\'association :</strong>
    Vous pouvez télécharger les statuts au format pdf <a href="http://www.jedisjeux.net/pdf/statuts_jdj.pdf" class="Link">ici</a>.
</p>
<p>
    <strong>Représentant légal :</strong>
    Loïc Frémont, président
</p>
<p>
    <strong>Directeur de publication :</strong>
    Loïc Frémont (<a href="mailto:loic_425@jedisjeux.net" class="Link">loic_425@jedisjeux.net</a>)
</p>
<p>
    <strong>Nous contacter :</strong>
    <a href="mailto:jedisjeux@jedisjeux.net" class="Link">jedisjeux@jedisjeux.net</a>
</p>
<p>
    <strong>Hébergement :</strong>
    GANDI SAS, Société par Actions Simplifiée au capital de 37.000€ ayant son siège social au
    15 place de la Nation à Paris (75011) FRANCE,
    immatriculée sous le numéro 423 093 459 RCS PARIS -
    N° TVA FR81423093459
</p>
<h3>Droit des internautes : droit d’accès et de rectification</h3>
<p>
    Conformément aux dispositions de la loi n°78-17 du 6 janvier 1978 relative à l’informatique,
    aux fichiers et aux libertés, les internautes disposent d’un droit d’accès, de modification,
    de rectification et de suppression des données qui les concernent. Ce droit s’exerce par voie postale,
    en justifiant de son identité, à l’adresse suivante :<br><br>
    Ministère de l’Éducation nationale, de la Jeunesse et de la Vie associative
    Direction de la jeunesse, de l’éducation populaire et de la vie associative,<br>
    95, avenue de France<br>
    75013 Paris
</p>
<p>
    La politique du site <a href="\'http://www.jedisjeux.net/" class="Link">www.jedisjeux.net</a> est en conformité avec la loi n°2004-575 du 21 juin 2004 pour la confiance dans l’économie numérique.
</p>
<h3>Informations sur les contenus</h3>
<p>
    Les textes sont présentés à titre indicatif sans valeur contractuelle.
    Les textes sont valables à la date de leur publication et ne sauraient engager la responsabilité de leurs auteurs ou de la structure.
    Toutes les informations de ce site peuvent être modifiées à tout moment et sans préavis par l\'association Jedisjeux.
</p>
<p>
    L\'association Jedisjeux ne pourrait être tenue responsable de l\'utilisation qui pourrait être faite
    par un tiers de ces informations qui ne sont données au public qu\'à titre indicatif
</p>
<h3>Propriétés</h3>
<p>
    Les contenus et les informations de ce site sont protégés par le droit sur la propriété intellectuelle.
    Il est interdit de les utiliser ou de les reproduire sans
    l\'autorisation expresse et préalable de l\'association Jedisjeux.
</p>
<h3>Cookies</h3>
<p>
Jedisjeux.net implante un cookie dans votre ordinateur.
</p>
<p>
Ce cookie enregistre des informations relatives à votre navigation sur notre site,
et stocke des informations que vous avez saisies durant votre visite :
</p>
<ul>
	<li>votre pseudo, numéro d\'utilisateur et identifiant de session</li>
	<li>les autorisations que vous possédez sur les différents accès du site</li>
	<li>la couleur de bannière que vous avez choisi</li>
</ul>
<p>
    Ainsi, vous n\'aurez pas besoin,
    lors de votre prochaine visite,
    de les saisir à nouveau.
    Nous pourrons les consulter lors de vos prochaines visites.
</p>
<p>
    La durée de conservation de ces informations dans votre ordinateur est de
    3600 secondes.
</p>
<p>
    Nous vous informons que vous pouvez vous opposer à l\'enregistrement de "cookies"
    en configurant votre navigateur de la manière suivante :
</p>
<h4>Pour Mozilla firefox</h4>
<ul>
	<li>Choisissez le menu "outil" puis "Options"</li>
	<li>Cliquez sur l\'icône "vie privée"</li>
	<li>Repérez le menu "cookie" et sélectionnez les options qui vous conviennent.</li>
</ul>
<h4>Pour Microsoft Internet Explorer</h4>
<ul>
	<li>choisissez le menu "Outils" puis "Options Internet"</li>
	<li>cliquez sur l\'onglet "Confidentialité"</li>
	<li>sélectionnez le niveau souhaité à l\'aide du curseur.</li>
</ul>
<h4>Pour Opéra 6.0 et au-delà</h4>
<ul>
	<li>choisissez le menu "Fichier" puis "Préférences"</li>
	<li>Vie Privée</li>
</ul>
<h4>Pour Google Chrome 14.x</h4>
<ul>
	<li>sur la page : <a href="chrome://settings/content" class="Link">chrome://settings/content</a></li>
	<li>dans la section Cookies, sélectionnez la valeur appropriée : "Interdire à tous les sites de stocker des données".</li>
</ul>
                ',
            ),
        );
    }
}