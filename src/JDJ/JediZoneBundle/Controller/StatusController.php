<?php

namespace JDJ\JediZoneBundle\Controller;

use JDJ\JediZoneBundle\Listener\NotificationListener;
use JDJ\JeuBundle\Entity\Jeu;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JDJ\JediZoneBundle\Service\StatusService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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
    const STATUS_CHANGE_MESSAGE = "Le statut du Jeu %%jeu%% est passé au Status %%status%%";
    const STATUS_ERROR_MESSAGE = "Une erreur s'est produite. Veuillez essayer plus tard.";

    /**
     * This function change the status of a game
     *
     * @Route("/jeu/{jeu}/status/{status}", name="change_status", options={"expose"=true})
     * @ParamConverter("jeu", class="JDJJeuBundle:Jeu")
     * @Method({"POST"})
     */
    public function changeStatusAction(Jeu $jeu, $status)
    {

        //Check user is granted
        if ($this->container->get('security.context')->isGranted('ROLE_WORKFLOW')) {
            //check status exist
            if (in_array($status, array_keys(\JDJ\JeuBundle\Entity\Jeu::getStatusList()))) {

                //Change status
                /* $this
                     ->getStatusService()
                     ->changeGameStatus($jeu, $status);*/

                //Notification creation
                //Gets the user that changes the game status
                $user = $this->get('security.context')->getToken()->getUser();

                $notificationListener = new NotificationListener(
                    $this->get('app.service.notification'),
                    $this->get('app.service.activity'),
                    $jeu,
                    $user
                );
                $dispatcher = $this->get('event_dispatcher');

                $dispatcher->addListener(
                    'kernel.response',
                    array($notificationListener, 'updateActivity')
                );

                //Prepare answer
                $response = new JsonResponse();
                $response->setStatusCode(JsonResponse::HTTP_OK);

                $response->setData(
                    array(
                        "message" => str_replace('%%status%%', $status, str_replace('%%jeu%%', $jeu->getLibelle(), self::STATUS_CHANGE_MESSAGE)),
                    )
                );

            } else {
                $response = new JsonResponse();
                $response->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);

                $response->setData(
                    array(
                        "message" => self::STATUS_ERROR_MESSAGE,
                    )
                );
            }
        } else {
            $response = new JsonResponse();
            $response->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);

            $response->setData(
                array(
                    "message" => self::STATUS_ERROR_MESSAGE,
                )
            );
        }

        return $response;

    }


    /**
     * TODO a virer
     *
     * @Route("/jeu/{jeu}/test", name="notif_test")
     * @ParamConverter("jeu", class="JDJJeuBundle:Jeu")
     */
    public function testAction(Jeu $jeu)
    {

        //Check user is granted
        if ($this->container->get('security.context')->isGranted('ROLE_WORKFLOW')) {


            //Gets the user that changes the game status
            $user = $this->get('security.context')->getToken()->getUser();


                //Notification creation
                $notificationListener = new NotificationListener(
                    $this->get('app.service.notification'),
                    $this->get('app.service.activity'),
                    $jeu,
                    $user
                );
                $dispatcher = $this->get('event_dispatcher');

                $dispatcher->addListener(
                    'kernel.response',
                    array($notificationListener, 'updateActivity')
                );


            } else {
                $response = new JsonResponse();
                $response->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);

                $response->setData(
                    array(
                        "message" => self::STATUS_ERROR_MESSAGE,
                    )
                );
            }

        $response = new JsonResponse();
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
