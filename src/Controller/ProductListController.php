<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\ProductList;
use App\Entity\ProductListItem;
use App\Factory\ProductListFactory;
use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductListController extends ResourceController
{
    /**
     * TODO handle directly productListItemController.
     *
     * @param int $productId
     *
     * @return Response
     */
    public function addProductAction(Request $request, $productId)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::UPDATE);
        /** @var ProductList $list */
        $list = $this->singleResourceProvider->get($configuration, $this->repository);

        if (null === $list) {
            /** @var ProductListFactory $factory */
            $factory = $this->factory;
            $list = $factory->createForCode($request->get('code'));
            $this->manager->persist($list);
        }

        /** @var ProductInterface $product */
        $product = $this->get('sylius.repository.product')->find($productId);

        if (null === $product) {
            throw new NotFoundHttpException();
        }

        /** @var ProductListItem $item */
        $item = $this->get('app.factory.product_list_item')->createNew();
        $item->setProduct($product);
        $item->setList($list);

        $list->addItem($item);
        $this->manager->flush();

        if (!$configuration->isHtmlRequest()) {
            return $this->viewHandler->handle($configuration, View::create($list, Response::HTTP_CREATED));
        }

        $view = View::create()
            ->setData([
                'configuration' => $configuration,
                'metadata' => $this->metadata,
                'resource' => $list,
                $this->metadata->getName() => $list,
            ])
            ->setTemplate($configuration->getTemplate(ResourceActions::UPDATE.'.html'));

        return $this->viewHandler->handle($configuration, $view);
    }

    /**
     * TODO handle directly productListItemController.
     *
     * @param $productId
     *
     * @return Response
     */
    public function removeProductAction(Request $request, $productId)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::UPDATE);
        /** @var ProductList $list */
        $list = $this->findOr404($configuration);

        $product = $this->get('sylius.repository.product')->find($productId);

        if (null === $product) {
            throw new NotFoundHttpException();
        }

        /** @var ProductListItem $item */
        $item = $this->get('app.repository.product_list_item')->findOneBy(['product' => $product, 'list' => $list]);

        if (null === $item) {
            throw new NotFoundHttpException();
        }

        $list->removeItem($item);
        $this->manager->remove($item);
        $this->manager->flush();

        if (!$configuration->isHtmlRequest()) {
            return $this->viewHandler->handle($configuration, View::create(null, Response::HTTP_NO_CONTENT));
        }

        $view = View::create()
            ->setData([
                'configuration' => $configuration,
                'metadata' => $this->metadata,
                'resource' => $list,
                $this->metadata->getName() => $list,
            ])
            ->setTemplate($configuration->getTemplate(ResourceActions::UPDATE.'.html'));

        return $this->viewHandler->handle($configuration, $view);
    }
}
