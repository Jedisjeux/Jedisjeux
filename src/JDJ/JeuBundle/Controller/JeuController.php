<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 11/06/2014
 * Time: 23:30
 */

namespace JDJ\JeuBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class JeuController extends Controller
{
    /**
     * Finds and displays a Jeu entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Jeu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Jeu entity.');
        }

        return $this->render('JDJJeuBundle:Jeu:show.html.twig', array(
                'jeu' => $entity,
            )
        );
    }
} 