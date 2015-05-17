<?php

namespace JDJ\JediZoneBundle\Controller;

use Proxies\__CG__\JDJ\JeuBundle\Entity\Jeu;
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
    public function changeStatusAction(\JDJ\JeuBundle\Entity\Jeu $jeu, $status)
    {

        //Check user is granted
        if ($this->container->get('security.context')->isGranted('ROLE_WORKFLOW')) {
            //check status exist
            if (in_array($status, array_keys(\JDJ\JeuBundle\Entity\Jeu::getStatusList()))) {

                //Change status
                $this
                    ->getStatusService()
                    ->changeGameStatus($jeu, $status);

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
     * @return StatusService
     */
    private function getStatusService()
    {
        return $this->container->get('app.service.status');
    }

}
