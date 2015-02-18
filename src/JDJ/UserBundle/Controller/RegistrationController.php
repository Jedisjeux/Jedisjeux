<?php

namespace JDJ\UserBundle\Controller;

use FOS\UserBundle\Entity\User;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class RegistrationController extends BaseController
{
    /**
     * This function overrides the Fosuserbundle registerAction function to add custom behaviour
     *
     * @param Request $request
     * @return null|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $userManager->updateUser($user);

                // authenticate the user right now
                $this->authenticateUser($user);

                $key = '_security.main.target_path';

                if (null === $response = $event->getResponse()) {
                    $request->getSession()->getFlashBag()->add('success', 'Votre compte a bien été créé. Veuillez maintenant vous connecter.');
                    $url = $this->container->get('router')->generate('fos_user_profile_show');
                    $response = new RedirectResponse($url);
                }

                return $response;
            }
        }

        return $this->container->get('templating')->renderResponse('JDJUserBundle:Registration:register.html.twig', array(
            'form' => $form->createView(),
            'entity' => $user,
        ));

    }

    /**
     * This function aloows the authentification of the user after registration
     *
     * @param User|\JDJ\UserBundle\Entity\User $user
     */
    private function authenticateUser(\JDJ\UserBundle\Entity\User $user)
    {
        $providerKey = 'main';
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());

        $this->container->get('security.context')->setToken($token);
    }
}