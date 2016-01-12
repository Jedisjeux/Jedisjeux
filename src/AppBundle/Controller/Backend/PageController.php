<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 06/01/2016
 * Time: 13:28
 */

namespace AppBundle\Controller\Backend;

use Doctrine\ODM\PHPCR\DocumentManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @Route("/page")
 */
class PageController extends Controller
{
    public function indexAction()
    {
        $queryBuilder = $this->getDocumentManager()->createQueryBuilder();
        $queryBuilder->where()->like()->field('');
    }

    /**
     * @return DocumentManager
     */
    protected function getDocumentManager()
    {
        return $this->get('doctrine_phpcr.odm.default_document_manager');
    }
}