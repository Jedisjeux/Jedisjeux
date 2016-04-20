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
use Doctrine\ORM\EntityRepository;
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

        $taxonomies = $this->getTaxonomyRepository()->findBy(array('name' => array('mecanismes', 'themes')));
        
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
     * @return EntityRepository
     */
    protected function getTaxonomyRepository()
    {
        return $this->get('sylius.repository.taxonomy');
    }
}