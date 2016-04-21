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
use Symfony\Component\HttpFoundation\Request;
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
        $articleContent = $this->findOr404($name);
        $article = $this->get('app.repository.article')->findOneBy(['documentId' => $articleContent->getId()]);

        return $this->render("frontend/content/page/article_content/show.html.twig", array(
            'article' => $article,
        ));
    }

    /**
     * @Route("/", name="article_content_index")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param string $name
     */
    public function indexAction(Request $request)
    {
        $criteria = $request->get('criteria', array());
        $sorting = $request->get('sorting', array());
        $template = $request->get('template', 'index.html.twig');

        $articles = $this
            ->getRepository()
            ->createPaginator($criteria, $sorting)
            ->setMaxPerPage($request->get('maxPerPage', 10))
            ->setCurrentPage($request->get('page', 1));

        return $this->render('frontend/content/page/article_content/'.$template, array(
            'articles' => $articles,
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