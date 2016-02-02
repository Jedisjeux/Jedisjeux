<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 02/02/16
 * Time: 07:58
 */

namespace AppBundle\Controller\Frontend\Content;

use AppBundle\Document\ArticleContent;
use Doctrine\ODM\PHPCR\DocumentManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sylius\Bundle\ResourceBundle\Doctrine\ODM\PHPCR\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @Route("/articles")
 */
class ArticleContentController extends Controller
{
    /**
     * @Route("/{name}", requirements={"name" = ".+"}, name="article_content_show")
     *
     * @param string $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($name)
    {
        $article = $this->findOr404($name);

        return $this->render("frontend/content/page/article_content/show.html.twig", array(
            'article' => $article,
        ));
    }

    /**
     * @param string $name
     * @return ArticleContent
     */
    protected function findOr404($name)
    {
        $page = $this
            ->getRepository()
            ->findOneBy(array('name' => $name));

        if (null === $page) {
            throw new NotFoundHttpException(sprintf("Article content %s not found", $name));
        }

        return $page;
    }

    /**
     * @return DocumentRepository
     */
    public function getRepository()
    {
        return $this->get('app.repository.article_content');
    }

    /**
     * @return DocumentManager
     */
    public function getManager()
    {
        return $this->get('app.manager.article_content');
    }
}