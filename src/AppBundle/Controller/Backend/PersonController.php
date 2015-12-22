<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 22/12/2015
 * Time: 13:49
 */

namespace AppBundle\Controller\Backend;

use JDJ\CoreBundle\Entity\EntityRepository;
use JDJ\LudographieBundle\Entity\Personne;
use JDJ\LudographieBundle\Form\PersonneType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @Route("/personne")
 */
class PersonController extends Controller
{
    /**
     * @Route("/", name="admin_person_index")
     *
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $criteria = $request->get('criteria', array());
        $sorting = $request->get('sorting', array('slug' => 'asc'));

        $persons = $this
            ->getRepository()
            ->createPaginator($criteria, $sorting)
            ->setCurrentPage($request->get('page', 1));

        return $this->render('backend/person/index.html.twig', array(
            'persons' => $persons,
        ));
    }

    /**
     * @Route("/new", name="admin_person_new")
     *
     * @param Request $request
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $person = new Personne();
        $form = $this->createForm(new PersonneType(), $person);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

            return $this->redirect($this->generateUrl(
                'admin_person_index',
                array('id' => $person->getId())
            ));
        }

        return $this->render('backend/person/new.html.twig', array(
            'person' => $person,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/edit", name="admin_person_edit")
     *
     * @ParamConverter("person", class="JDJJeuBundle:Person")
     *
     * @param Request $request
     * @param Personne $person
     *
     * @return array
     */
    public function updateAction(Request $request, Personne $person)
    {
        $form = $this->createForm(new PersonneType(), $person);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

            return $this->redirect($this->generateUrl(
                'admin_person_index',
                array('id' => $person->getId())
            ));
        }

        return $this->render('backend/person/edit.html.twig', array(
            'person' => $person,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/delete", name="admin_person_delete")
     *
     * @ParamConverter("person", class="JDJJeuBundle:Person")
     *
     * @param Personne $person
     *
     * @return RedirectResponse
     */
    public function deleteAction(Personne $person)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($person);
        $em->flush();

        return $this->redirect($this->generateUrl(
            'admin_person_index'
        ));
    }

    /**
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->getDoctrine()->getRepository('JDJLudographieBundle:Personne');
    }
}