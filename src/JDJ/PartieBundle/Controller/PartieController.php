<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 27/08/2014
 * Time: 23:35
 */

namespace JDJ\PartieBundle\Controller;


use JDJ\PartieBundle\Entity\Partie;
use JDJ\PartieBundle\Form\PartieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PartieController extends Controller
{
    /**
     * Creates a new Partie entity.
     */
    public function createAction(Request $request)
    {
        $user = $this->get('security.context')->getToken()->getUser();

        $entity = new Partie();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setAuthor($user);
            $em->persist($entity);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'La partie a bien été enregistrée');
            return $this->redirect($this->generateUrl('client'));
        }

        return $this->render('JDJComptaBundle:Client:edit.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Client entity.
     *
     * @param Partie $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Partie $entity)
    {
        $form = $this->createForm(new PartieType(), $entity, array(
            'action' => $this->generateUrl('partie_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Créer'));

        return $form;
    }

    /**
     * Finds and displays a Partie entity.
     *
     */
    public function showAction($id, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJPartieBundle:Partie')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Partie entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJPartieBundle:Partie:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Creates a form to delete a Client entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('partie_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Supprimer'))
            ->getForm()
            ;
    }
} 