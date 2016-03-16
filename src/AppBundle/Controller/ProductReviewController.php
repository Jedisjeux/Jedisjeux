<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 16/03/2016
 * Time: 13:40
 */

namespace AppBundle\Controller;

use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductReviewController extends ResourceController
{
    /**
     * @param Request $request
     *
     * @param $template
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function partialIndexAction(Request $request, $template)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::INDEX);
        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        $view = View::create($resources);

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate(":partial/product_review/".$template)
                ->setTemplateVar($this->metadata->getPluralName())
                ->setData([
                    'metadata' => $this->metadata,
                    'resources' => $resources,
                    $this->metadata->getPluralName() => $resources,
                ])
            ;
        }

        return $this->viewHandler->handle($configuration, $view);
    }
}