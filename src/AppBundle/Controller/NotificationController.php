<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 19/04/16
 * Time: 13:31
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Notification;
use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class NotificationController extends ResourceController
{
    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function readAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::UPDATE);

        /** @var Notification $resource */
        $resource = $this->findOr404($configuration);
        $resource->setRead(true);

        $this->eventDispatcher->dispatchPreEvent(ResourceActions::UPDATE, $configuration, $resource);
        $this->manager->flush();
        $this->eventDispatcher->dispatchPostEvent(ResourceActions::UPDATE, $configuration, $resource);

        if (!$configuration->isHtmlRequest()) {
            return $this->viewHandler->handle($configuration, View::create($resource, 204));
        }

        return $this->redirectHandler->redirectToIndex($configuration, $resource);
    }
}