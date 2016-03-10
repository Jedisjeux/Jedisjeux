<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 09/03/2016
 * Time: 10:28
 */

namespace AppBundle\Controller;

use AppBundle\Repository\ProductRepository;
use Doctrine\ORM\EntityRepository;
use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductController extends ResourceController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexWithTaxonsAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::INDEX);

        $taxonomies = $this->getTaxonomyRepository()->findBy(array('name' => array('mecanismes', 'themes')));

        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        $view = View::create($resources);

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate($configuration->getTemplate(ResourceActions::INDEX))
                ->setTemplateVar($this->metadata->getPluralName())
                ->setData([
                    'taxonomies' => $taxonomies,
                    'metadata' => $this->metadata,
                    'resources' => $resources,
                    $this->metadata->getPluralName() => $resources,
                ])
            ;
        }

        return $this->viewHandler->handle($configuration, $view);
    }

    /**
     * @param Request $request
     * @param $permalink
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexByTaxonAction(Request $request, $permalink)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::INDEX);

        $taxon = $this->get('sylius.repository.taxon')->findOneBy(array('permalink' => $permalink));
        if (!isset($taxon)) {
            throw new NotFoundHttpException('Requested taxon does not exist.');
        }

        $taxonomies = $this->getTaxonomyRepository()->findBy(array('name' => array('mecanismes', 'themes')));

        /** @var ProductRepository $repository */
        $repository = $this->repository;

        $resources = $repository
            ->createByTaxonPaginator($taxon, $request->get('criteria', $configuration->getCriteria()), $request->get('sorting', $configuration->getSorting()))
            ->setMaxPerPage($configuration->getPaginationMaxPerPage())
            ->setCurrentPage($request->get('page', 1));

        $view = View::create($resources);

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate($configuration->getTemplate('indexByTaxon.html'))
                ->setTemplateVar($this->metadata->getPluralName())
                ->setData([
                    'metadata' => $this->metadata,
                    'taxon' => $taxon,
                    'taxonomies' => $taxonomies,
                    'resources' => $resources,
                    $this->metadata->getPluralName() => $resources,
                ]);
        }

        return $this->viewHandler->handle($configuration, $view);
    }

    /**
     * @return EntityRepository
     */
    protected function getTaxonomyRepository()
    {
        return $this->get('sylius.repository.taxonomy');
    }
}