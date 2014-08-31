<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 11/06/2014
 * Time: 23:30
 */

namespace JDJ\JeuBundle\Controller;


use Doctrine\Common\Collections\ArrayCollection;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\JeuBundle\Form\JeuType;
use JDJ\UserReviewBundle\Entity\JeuNote;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class JeuController extends Controller
{
    /**
     * Finds and displays a Jeu entity.
     *
     */
    public function showAction($id, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Jeu $entity */
        $entity = $em->getRepository('JDJJeuBundle:Jeu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Jeu entity.');
        }

        /**
         * Redirect the slug is incorrect
         */
        if ($slug !== $entity->getSlug()) {
            return $this->redirect($this->generateUrl('jeu_show', array(
                        'id' => $id,
                        'slug' => $entity->getSlug(),
                    )
                )
            );
        }

        $jeuNotes = $em->getRepository('JDJUserReviewBundle:JeuNote')->findBy(array(
            'jeu' => $entity,
        ));

        $userReviews = new ArrayCollection();

        /** @var JeuNote $jeuNote */
        foreach($jeuNotes as $jeuNote)
        {
            if ($jeuNote->hasUserReview()) {
                $userReviews[] = $jeuNote->getUserReview();
            }
        }

        return $this->render('JDJJeuBundle:Jeu:show.html.twig', array(
                'jeu' => $entity,
                'userReviews' => $userReviews,
            )
        );
    }

    /**
     * Displays a form to edit an existing Caracteristique entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Jeu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Jeu entity.');
        }

        $form = $this->createEditForm($entity);

        return $this->render('JDJJeuBundle:Jeu:edit.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Méthode utilisée lors de l'initialisation du site
     */
    public function initialisationAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        for ($i=1 ; $i<20 ; $i++) {
            $entities[] = $em->getRepository('JDJJeuBundle:Jeu')->find($i);
        }


        /** @var Jeu $entity */
        foreach($entities as $entity) {
            $entity->setIntro($this->nl2p($entity->getIntro()));
            $entity->setBut($this->nl2p($entity->getBut()));
            $entity->setDescription($this->nl2p($entity->getDescription()));
            $em->flush($entity);
        }
        exit;
    }

    private function nl2p($text) {
        $text = trim($text);
        // on remplace les retour à la ligne par des <br />
        $text = preg_replace("/\n\r?/", "<br />", $text);
        // on crée les paragraphes autour des sauts de ligne
        $text = preg_replace("/(<br \/>){2,}/", "</p>\n<p>", $text);
        return "<p>".$text."</p>";

    }

    /**
     * Creates a form to edit a Jeu entity.
     *
     * @param Jeu $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Jeu $entity)
    {
        $form = $this->createForm(new JeuType(), $entity, array(
            'action' => $this->generateUrl('jeu_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }

    /**
     * Edits an existing Jeu entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJJeuBundle:Jeu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Jeu entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('jeu_show', array(
                        'id' => $id,
                        'slug' => "test",
                    )
                )
            );
        }

        return $this->render('JDJJeuBundle:Jeu:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }
} 