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

use App\Entity\Notification;
use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class NotificationController extends ResourceController
{
    /**
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
