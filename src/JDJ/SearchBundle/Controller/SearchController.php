<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 20/09/2014
 * Time: 14:16
 */

namespace JDJ\SearchBundle\Controller;


use Elastica\Facet\Terms;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\LudographieBundle\Entity\Personne;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class SearchController extends Controller
{

    public function searchQueryAction(Request $request)
    {
        $query = $request->get("query");
        $paginator = $this->findByQuery($query);
        $paginator->setMaxPerPage(16);
        $paginator->setCurrentPage($request->get('page', 1));

        if ($paginator->getNbResults() === 1) {
            return $this->redirectToTheUniqueResult($paginator);
        }

        $types = array();
        foreach($paginator as $result) {
            if ($result instanceof Jeu) {
                $types[] = "jeu";
            } elseif ($result instanceof Personne) {
                $types[] = "personne";
            }

        }

        return $this->render('JDJSearchBundle:Search:searchResults.html.twig', array(
            'results' => $paginator,
            'types' => $types,
        ));
    }

    /**
     * @param $query
     * @return Pagerfanta
     */
    private function findByQuery($query)
    {
        /** @var TransformedFinder $finder */
        $finder = $this->container->get('fos_elastica.finder.search');

        /** @var Pagerfanta $userPaginator */
        $paginator = $finder->findPaginated($query);
        return $paginator;

    }

    /**
     * @param Pagerfanta $paginator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function redirectToTheUniqueResult(Pagerfanta $paginator)
    {
        $current = $paginator->getIterator()->current();

        if ($current instanceof Jeu) {
            /** @var Jeu $jeu */
            $jeu = $current;
            return $this->redirect($this->generateUrl('jeu_show', array(
                    'id' => $jeu->getId(),
                    'slug' => $jeu->getSlug(),
                )
            ));
        }

        if ($current instanceof Personne) {
            /** @var Personne $personne */
            $personne = $current;
            return $this->redirect($this->generateUrl('personne_show', array(
                    'id' => $personne->getId(),
                    'slug' => $personne->getSlug(),
                )
            ));
        }
    }
} 