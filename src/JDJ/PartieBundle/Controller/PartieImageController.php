<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 24/03/15
 * Time: 13:27
 */

namespace JDJ\PartieBundle\Controller;

use JDJ\PartieBundle\Entity\PartieImage;
use JDJ\PartieBundle\Form\PartieImageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PartieImageController
 * @package JDJ\PartieBundle\Controller
 */
class PartieImageController extends Controller
{
    /**
     * Displays a form to create a new JeuImage entity.
     *
     * @Route("/partie/{partie_id}/image", name="partie_image_new")
     * @ParamConverter("partie", class="JDJPartieBundle:Partie", options={"id" = "partie_id"})
     */
    public function newAction($partie)
    {
        $entity = new PartieImage();
        $entity->setPartie($partie);
        $form   = $this->createCreateForm($entity);

        return $this->render('partie/image/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new PartieImage entity.
     *
     * @Route("/partie/image/create", name="partie_image_create")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $entity = new PartieImage();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $entity->upload();

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('partie_show', array(
                'id' => $entity->getPartie()->getId(),
                'slug' => $entity->getPartie()->getJeu()->getSlug(),
            )));
        }

        return $this->render('jeu/image/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Mechanism entity.
     *
     * @param PartieImage $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(PartieImage $entity)
    {
        $form = $this->createForm(new PartieImageType(), $entity, array(
            'action' => $this->generateUrl('partie_image_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Créer'));

        return $form;
    }
} 