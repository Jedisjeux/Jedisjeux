<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use FOS\RestBundle\View\View;
use Sylius\Bundle\UserBundle\Controller\UserController as BaseUserController;
use Sylius\Bundle\UserBundle\UserEvents;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class UserController extends BaseUserController
{
    /**
     * Override to enable user
     *
     * @param Request $request
     * @param string $token
     *
     * @return Response
     */
    public function verifyAction(Request $request, $token)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $redirectRoute = $request->attributes->get('_sylius[redirect]', null, true);

        $response = $this->redirectToRoute($redirectRoute);

        /** @var UserInterface $user */
        $user = $this->repository->findOneBy(['emailVerificationToken' => $token]);
        if (null === $user) {
            if (!$configuration->isHtmlRequest()) {
                return $this->viewHandler->handle($configuration, View::create($configuration, Response::HTTP_BAD_REQUEST));
            }

            $this->addFlash('error', 'sylius.user.verification.error');

            return $this->redirectToRoute($redirectRoute);
        }

        $eventDispatcher = $this->container->get('event_dispatcher');
        $eventDispatcher->dispatch(UserEvents::PRE_EMAIL_VERIFICATION, new GenericEvent($user));

        $user->setVerifiedAt(new \DateTime());
        $user->setEmailVerificationToken(null);
        $user->enable();

        $this->manager->flush();

        $eventDispatcher->dispatch(UserEvents::POST_EMAIL_VERIFICATION, new GenericEvent($user));

        if (!$configuration->isHtmlRequest()) {
            return $this->viewHandler->handle($configuration, View::create($user));
        }

        $flashMessage = $request->attributes->get('_sylius[flash]', 'sylius.user.verification.success');
        $this->addFlash('success', $flashMessage);

        return $response;
    }
}
