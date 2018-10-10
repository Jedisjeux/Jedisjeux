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

use App\Entity\ProductVariant;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductVariantController extends ResourceController
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws HttpException
     */
    public function updatePositionsAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::UPDATE);
        $productVariantsToUpdate = $request->get('productVariants');

        if ($configuration->getParameters()->get('csrf_protection', true) &&  !$this->isCsrfTokenValid('update-product-variant-position', $request->request->get('_csrf_token'))) {
            throw new HttpException(Response::HTTP_FORBIDDEN, 'Invalid csrf token.');
        }

        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'], true) && null !== $productVariantsToUpdate) {
            foreach ($productVariantsToUpdate as $productVariantToUpdate) {
                if (!is_numeric($productVariantToUpdate['position'])) {
                    throw new HttpException(
                        Response::HTTP_NOT_ACCEPTABLE,
                        sprintf('The product variant position "%s" is invalid.', $productVariantToUpdate['position'])
                    );
                }

                /** @var ProductVariant $productVariant */
                $productVariant = $this->repository->findOneBy(['id' => $productVariantToUpdate['id']]);
                $productVariant->setPosition($productVariantToUpdate['position']);
                $this->manager->flush();
            }
        }

        return new JsonResponse();
    }
}
