<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 13/04/2016
 * Time: 13:48
 */

namespace AppBundle\Controller;

use AppBundle\Entity\CustomerList;
use AppBundle\Repository\CustomerListElementRepository;
use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\ResourceActions;
use Sylius\Component\User\Model\CustomerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CustomerListElementController extends ResourceController
{
    public function gameLibraryShowAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::INDEX);

        /** @var CustomerInterface $customer */
        $customer = $this->get('sylius.repository.customer')->find($request->get('customerId'));

        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        $view = View::create($resources);

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate($configuration->getTemplate(ResourceActions::INDEX))
                ->setTemplateVar($this->metadata->getPluralName())
                ->setData([
                    'customer' => $customer,
                    'metadata' => $this->metadata,
                    'resources' => $resources,
                    $this->metadata->getPluralName() => $resources,
                ])
            ;
        }

        return $this->viewHandler->handle($configuration, $view);
    }
}