<?php

namespace JDJ\JediZoneBundle\Controller;

use JDJ\CollectionBundle\Entity\UserGameAttribute;
use JDJ\CollectionBundle\Service\CollectionService;
use JDJ\CollectionBundle\Service\UserGameAttributeService;
use JDJ\JediZoneBundle\Entity\Activity;
use JDJ\JediZoneBundle\Entity\Notification;
use JDJ\JediZoneBundle\Service\NotificationService;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\UserBundle\Entity\User;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use JDJ\CollectionBundle\Entity\Collection;
use JDJ\CollectionBundle\Form\CollectionType;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class NotificationController
 *
 * @package JDJ\JediZoneBundle\Controller
 * @Route("/jedizone")
 */
class NotificationController extends Controller
{
    /**
     * The jedizone page where users can see their activity and notifications
     *
     * @Route("/", name="jedizone_index")
     * @Route("/{status}/", name="jedizone_status_filter_index")
     * @Route("/{notificationType}", name="jedizone_type_filter_index")
     * @Security("has_role('ROLE_WORKFLOW')")
     *
     * @param null $status
     * @param null $notificationType
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function indexAction($status = null, $notificationType = null)
    {

        $user = $this->get('security.context')->getToken()->getUser();

        //the notification list
        $notifications = $this
            ->getNotificationService()
            ->getNotificationsFromUser($user, $status, $notificationType);

        //The filter informations
        $notificationFilterCount = $this
            ->getNotificationService()
            ->getNotificationsFromUserCount($user);

        return $this->render('jedizone/index.html.twig', array(
            'notifications' => $notifications,
            'notificationFilterCount' => $notificationFilterCount,
        ));

    }


    /**
     * The jedizone page where users can see their notifications
     *
     * @Route("/{notification}/show", name="notification_show")
     * @ParamConverter("notification", class="JDJJediZoneBundle:Notification")
     * @param Notification $notification
     * @return Response
     */
    public function showAction(Notification $notification)
    {
        return $this->render('jedizone/notification/show.html.twig', array(
            'notification' => $notification,
        ));

    }


    /**
     * @return NotificationService
     */
    private function getNotificationService()
    {
        return $this->container->get('app.service.notification');
    }

}
