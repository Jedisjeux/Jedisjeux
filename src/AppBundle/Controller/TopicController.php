<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 29/02/2016
 * Time: 13:44
 */

namespace AppBundle\Controller;

use AppBundle\Repository\TopicRepository;
use Hateoas\Configuration\Route;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\TaxonomyBundle\Doctrine\ORM\TaxonomyRepository;
use Sylius\Bundle\TaxonomyBundle\Doctrine\ORM\TaxonRepository;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Model\TaxonomyInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicController extends ResourceController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function indexWithTaxonsAction(Request $request)
    {
        /** @var TaxonomyInterface $taxonomy */
        $taxonomy = $this->getTaxonomyRepository()->findOneBy(array('name' => 'forum'));
        $taxons = $this->getTaxonRepository()->getTaxonsAsList($taxonomy);

        $this->isGrantedOr403('index');

        $criteria = $this->config->getCriteria();
        $sorting = $this->config->getSorting();

        $repository = $this->getRepository();

        if ($this->config->isPaginated()) {
            $resources = $this->resourceResolver->getResource(
                $repository,
                'createPaginator',
                array($criteria, $sorting)
            );
            $resources->setCurrentPage($request->get('page', 1), true, true);
            $resources->setMaxPerPage($this->config->getPaginationMaxPerPage());

            if ($this->config->isApiRequest()) {
                $resources = $this->getPagerfantaFactory()->createRepresentation(
                    $resources,
                    new Route(
                        $request->attributes->get('_route'),
                        array_merge($request->attributes->get('_route_params'), $request->query->all())
                    )
                );
            }
        } else {
            $resources = $this->resourceResolver->getResource(
                $repository,
                'findBy',
                array($criteria, $sorting, $this->config->getLimit())
            );
        }

        $view = $this
            ->view()
            ->setTemplate($this->config->getTemplate('index.html'))
            ->setTemplateVar($this->config->getPluralResourceName())
            ->setData(array(
                'taxons' => $taxons,
                'topics' => $resources,
            ));

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     * @param $permalink
     * @return Response
     */
    public function indexByTaxonSlugAction(Request $request, $permalink)
    {
        /** @var TaxonomyInterface $taxonomy */
        $taxonomy = $this->getTaxonomyRepository()->findOneBy(array('name' => 'forum'));
        /** @var TaxonInterface $taxon */
        $taxon = $this->getTaxonRepository()->findOneBy(array('slug' => $permalink));

        if (!isset($taxon)) {
            throw new NotFoundHttpException('Requested taxon does not exist.');
        }

        $this->isGrantedOr403('index');

        $criteria = $this->config->getCriteria();
        $sorting = $this->config->getSorting();

        $repository = $this->getRepository();

        if ($this->config->isPaginated()) {
            /** @var TopicRepository $repository */
            $repository = $this->getRepository();
            $resources = $repository->createByTaxonPaginator($taxon, $criteria, $sorting);
            $resources->setCurrentPage($request->get('page', 1), true, true);
            $resources->setMaxPerPage($this->config->getPaginationMaxPerPage());

            if ($this->config->isApiRequest()) {
                $resources = $this->getPagerfantaFactory()->createRepresentation(
                    $resources,
                    new Route(
                        $request->attributes->get('_route'),
                        array_merge($request->attributes->get('_route_params'), $request->query->all())
                    )
                );
            }
        } else {
            $resources = $this->resourceResolver->getResource(
                $repository,
                'findBy',
                array($criteria, $sorting, $this->config->getLimit())
            );
        }

        $view = $this
            ->view()
            ->setTemplate($this->config->getTemplate('index.html'))
            ->setTemplateVar($this->config->getPluralResourceName())
            ->setData(array(
                'taxon' => $taxon,
                'taxons' => $taxonomy->getTaxons(),
                'topics' => $resources,
            ));

        return $this->handleView($view);
    }

    /**
     * @return TaxonomyRepository
     */
    protected function getTaxonomyRepository()
    {
        return $this->get('sylius.repository.taxonomy');
    }

    /**
     * @return TaxonRepository
     */
    protected function getTaxonRepository()
    {
        return $this->get('sylius.repository.taxon');
    }
}