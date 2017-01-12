<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 01/02/2016
 * Time: 13:44
 */

namespace AppBundle\Controller\Backend\Content;

use Doctrine\ODM\PHPCR\DocumentManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sylius\Bundle\ResourceBundle\Doctrine\ODM\PHPCR\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @Route("/content/page/article")
 */
class ArticleContentController extends Controller
{
    /**
     * @Route("/", name="admin_article_content_index")
     *
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $criteria = $request->get('criteria', array());
        $sorting = $request->get('sorting', array('title' => 'asc'));

        $articles = $this
            ->getRepository()
            ->createPaginator($criteria, $sorting)
            ->setCurrentPage($request->get('page', 1));

        return $this->render('backend/content/page/article_content/index.html.twig', array(
            'articles' => $articles,
        ));
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