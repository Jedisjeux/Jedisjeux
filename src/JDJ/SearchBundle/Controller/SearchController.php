<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 20/09/2014
 * Time: 14:16
 */

namespace JDJ\SearchBundle\Controller;


use FOS\ElasticaBundle\Finder\TransformedFinder;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class SearchController extends Controller
{

    public function searchQueryAction($query)
    {
        /** @var TransformedFinder $finder */
        $finder = $this->container->get('fos_elastica.finder.search.user');

        /** @var Pagerfanta $userPaginator */
        $userPaginator = $finder->findPaginated($query);

        return $this->render('JDJSearchBundle:Search:searchResults.html.twig', array('users' => $userPaginator));
    }
} 