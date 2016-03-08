<?php

namespace JDJ\JediZoneBundle\Controller;

use JDJ\JediZoneBundle\Entity\Notification;
use JDJ\JediZoneBundle\Service\NotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

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
     *
     * @param Notification $notification
     *
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
