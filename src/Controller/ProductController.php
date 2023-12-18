<?php

/*
 * This file is part of jedisjeux project.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Taxon;
use App\Repository\TaxonRepository;
use FOS\RestBundle\View\View;
use SM\Factory\Factory;
use SM\StateMachine\StateMachineInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        if ($configuration->isHtmlRequest()) {
            return $this->render($configuration->getTemplate(ResourceActions::INDEX . '.html'), [
                'rootTaxons' => $this->getRootTaxons(),
                'metadata' => $this->metadata,
                'resources' => $resources,
                $this->metadata->getPluralName() => $resources,
            ]);
        }

        return $this->createRestView($configuration, $resources);
    }

    /**
     * @param Request $request
     * @param string  $slug
     *
     * @return Response
     */
    public function indexByTaxonAction(Request $request, $slug)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::INDEX);

        $taxon = $this->get('sylius.repository.taxon')->findOneBySlug($slug, $this->getParameter('locale'));
        if (!isset($taxon)) {
            throw new NotFoundHttpException('Requested taxon does not exist.');
        }

        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        if ($configuration->isHtmlRequest()) {
            return $this->render($configuration->getTemplate('indexByTaxon.html'), [
                'metadata' => $this->metadata,
                'taxon' => $taxon,
                'rootTaxons' => $this->getRootTaxons(),
                'resources' => $resources,
                $this->metadata->getPluralName() => $resources,
            ]);
        }

        return $this->createRestView($configuration, $resources);
    }

    /**
     * @param Request $request
     * @param string  $slug
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

        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        if ($configuration->isHtmlRequest()) {
            return $this->render($configuration->getTemplate('indexByPerson.html'), [
                'metadata' => $this->metadata,
                'rootTaxons' => $this->getRootTaxons(),
                'person' => $person,
                'resources' => $resources,
                $this->metadata->getPluralName() => $resources,
            ]);
        }

        return $this->createRestView($configuration, $resources);
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

        if ($configuration->isHtmlRequest()) {
            return $this->render($configuration->getTemplate(ResourceActions::SHOW . '.html'), [
                'configuration' => $configuration,
                'metadata' => $this->metadata,
                'resource' => $resource,
                $this->metadata->getName() => $resource,
                'state_machine' => $this->getStateMachineProduct($resource),
            ]);
        }

        return $this->createRestView($configuration, $resource);
    }

    /**
     * @param ProductInterface $product
     *
     * @return \SM\StateMachine\StateMachineInterface
     */
    protected function getStateMachineProduct(ProductInterface $product): StateMachineInterface
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
            ],
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
