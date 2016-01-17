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
        $page = $this->findPageOr404($data['name']);

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
    protected function findPageOr404($id)
    {
        $page = $this
            ->getRepository()
            ->findStaticContent($id);

        if (null === $page) {
            throw new NotFoundHttpException(sprintf("Page %s not found", $id));
        }

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
                'body' => '<h3>Droit des internautes : droit d’accès et de rectification</h3>

<p>Conformément aux dispositions de la loi n°78-17 du 6 janvier 1978 relative à l’informatique, aux fichiers et aux libertés, les internautes&nbsp;disposent d’un droit d’accès, de modification, de rectification et de suppression des données qui les concernent. Ce droit&nbsp;s’exerce par voie postale, en justifiant de son identité, à l’adresse suivante :</p>

<p>Ministère de l’Éducation nationale, de la Jeunesse et de la Vie associative Direction de la jeunesse, de l’éducation populaire et de la vie associative,<br>
95, avenue de France<br>
75013 Paris&nbsp;</p>

<p>La politique du site&nbsp;<a class="Link" href="http://www.jedisjeux.net/\'http://www.jedisjeux.net/" style="color: rgb(27, 117, 167); text-align: right;">www.jedisjeux.net</a>&nbsp;est en conformité avec la loi n°2004-575 du 21 juin 2004 pour la confiance dans l’économie numérique.</p>

<h3>Informations sur les contenus</h3>

<p>Les textes sont présentés à titre indicatif sans valeur contractuelle. Les textes sont valables à la date de leur publication et ne sauraient engager la responsabilité de leurs auteurs ou de la structure. Toutes les informations de ce site peuvent être modifiées à tout moment et sans préavis par l\'association Jedisjeux.&nbsp;</p>

<p>L\'association Jedisjeux ne pourrait être tenue responsable de l\'utilisation qui pourrait être faite par un tiers de ces informations qui ne sont données au public qu\'à titre indicatif.</p>',
            ),
        );
    }
}