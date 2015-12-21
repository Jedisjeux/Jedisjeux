<?php

namespace JDJ\JediZoneBundle\Controller;

use JDJ\JediZoneBundle\Entity\Notification;
use JDJ\JediZoneBundle\Listener\NotificationListener;
use JDJ\JeuBundle\Entity\Jeu;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JDJ\JediZoneBundle\Service\StatusService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\VarDumper\VarDumper;

class StatusController extends Controller
{

    /**
     * This function change the status of a game
     *
     * @Route("/jeu/{jeu}/status/{status}/{action}", name="change_status", options={"expose"=true})
     * @ParamConverter("jeu", class="JDJJeuBundle:Jeu")
     * @Method({"POST"})
     * @Security("has_role('ROLE_WORKFLOW')")
     *
     * @param Jeu $jeu
     * @param $status
     * @param $action
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function changeStatusAction(Jeu $jeu, $status, $action)
    {

        //check status exist
        if (in_array($status, array_keys(Jeu::getStatusList()))) {

            //Change status
            if(Notification::ACTION_ACCEPT === $action) {
                //Only if the workflow user accepts
                $this
                    ->getStatusService()
                    ->changeGameStatus($jeu, $status);
            }

            //Notification creation
            //Gets the user that changes the game status
            $user = $this->get('security.context')->getToken()->getUser();

            $notificationListener = new NotificationListener(
                $this->get('app.service.notification'),
                $this->get('app.service.activity'),
                $jeu,
                $user,
                $action,
                $_POST['comment']
            );
            $dispatcher = $this->get('event_dispatcher');

            $dispatcher->addListener(
                'kernel.response',
                array($notificationListener, 'updateActivity')
            );

            //Prepare answer
            $response = new JsonResponse();
            $response->setStatusCode(JsonResponse::HTTP_OK);

            if(Notification::ACTION_ACCEPT === $action) {
                $message = $this
                    ->get('translator')
                    ->trans('STATUS_CHANGE_MESSAGE', array(
                        "%status%" => $this
                            ->get('translator')
                            ->trans(/** @Ignore */$status),
                        "%jeu%" => $jeu->getName(),
                    ));
            } else {
                $message = $this
                    ->get('translator')
                    ->trans('STATUS_DECLINE_MESSAGE', array(
                        "%status%" => $this
                            ->get('translator')
                            ->trans(/** @Ignore */$status),
                        "%jeu%" => $jeu->getName(),
                    ));
            }
            $response->setData(
                array(
                    "message" => $message,
                )
            );

        } else {
            $response = new JsonResponse();
            $response->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);

            $response->setData(
                array(
                    "message" =>  $this
                        ->get('translator')
                        ->trans('STATUS_ERROR_MESSAGE'),
                )
            );
        }


        return $response;

    }


    /**
     * @return StatusService
     */
    private function getStatusService()
    {
        return $this->container->get('app.service.status');
    }

}
