<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 09/03/2016
 * Time: 10:28
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Taxon;
use AppBundle\Repository\ProductRepository;
use AppBundle\Repository\TaxonRepository;
use Doctrine\ORM\EntityRepository;
use FOS\RestBundle\View\View;
use SM\Factory\Factory;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductController extends ResourceController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexWithTaxonsAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::INDEX);

        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        $view = View::create($resources);

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate($configuration->getTemplate(ResourceActions::INDEX))
                ->setTemplateVar($this->metadata->getPluralName())
                ->setData([
                    'rootTaxons' => $this->getRootTaxons(),
                    'metadata' => $this->metadata,
                    'resources' => $resources,
                    $this->metadata->getPluralName() => $resources,
                ]);
        }

        return $this->viewHandler->handle($configuration, $view);
    }

    /**
     * @param Request $request
     * @param string $permalink
     *
     * @return Response
     */
    public function indexByTaxonAction(Request $request, $permalink)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::INDEX);

        $taxon = $this->get('sylius.repository.taxon')->findOneByPermalink($permalink);
        if (!isset($taxon)) {
            throw new NotFoundHttpException('Requested taxon does not exist.');
        }

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
                    'rootTaxons' => $this->getRootTaxons(),
                    'resources' => $resources,
                    $this->metadata->getPluralName() => $resources,
                ]);
        }

        return $this->viewHandler->handle($configuration, $view);
    }

    /**
     * @param Request $request
     * @param string $slug
     *
     * @return Response
     */
    public function indexByPersonAction(Request $request, $slug)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::INDEX);

        $person = $this->get('app.repository.person')->findOneBy(['slug' => $slug]);
        if (!isset($person)) {
            throw new NotFoundHttpException('Requested person does not exist.');
        }

        /** @var ProductRepository $repository */
        $repository = $this->repository;

        $criteria = array_merge($request->get('criteria', $configuration->getCriteria()), [
            'person' => $person,
        ]);

        $resources = $repository
            ->createFilterPaginator($criteria, $request->get('sorting', $configuration->getSorting()))
            ->setMaxPerPage($configuration->getPaginationMaxPerPage())
            ->setCurrentPage($request->get('page', 1));

        $view = View::create($resources);

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate($configuration->getTemplate('indexByPerson.html'))
                ->setTemplateVar($this->metadata->getPluralName())
                ->setData([
                    'metadata' => $this->metadata,
                    'rootTaxons' => $this->getRootTaxons(),
                    'person' => $person,
                    'resources' => $resources,
                    $this->metadata->getPluralName() => $resources,
                ]);
        }

        return $this->viewHandler->handle($configuration, $view);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function showStatusAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::SHOW);
        /** @var ProductInterface $resource */
        $resource = $this->findOr404($configuration);

        $this->eventDispatcher->dispatch(ResourceActions::SHOW, $configuration, $resource);

        $view = View::create($resource);

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate($configuration->getTemplate(ResourceActions::SHOW . '.html'))
                ->setTemplateVar($this->metadata->getName())
                ->setData([
                    'configuration' => $configuration,
                    'metadata' => $this->metadata,
                    'resource' => $resource,
                    $this->metadata->getName() => $resource,
                    'state_machine' => $this->getStateMachine($resource),
                ])
            ;
        }

        return $this->viewHandler->handle($configuration, $view);
    }

    /**
     * @param ProductInterface $product
     *
     * @return \SM\StateMachine\StateMachineInterface
     */
    protected function getStateMachine(ProductInterface $product)
    {
        /** @var Factory $factory */
        $factory = $this->get('sm.factory');

        return $factory->get($product, 'sylius_product');
    }

    /**
     * @return array|Taxon[]
     */
    protected function getRootTaxons()
    {
        return $this->getTaxonRepository()->findBy([
            'code' => [
                Taxon::CODE_MECHANISM,
                Taxon::CODE_THEME,
                Taxon::CODE_TARGET_AUDIENCE,
            ]
        ]);
    }

    /**
     * @return TaxonRepository
     */
    protected function getTaxonRepository()
    {
        return $this->get('sylius.repository.taxon');
    }
}