<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 21/12/2015
 * Time: 16:08
 */

namespace AppBundle\Controller\Backend;

use JDJ\JeuBundle\Entity\Mechanism;
use JDJ\JeuBundle\Form\MechanismType;
use JDJ\JeuBundle\Repository\MechanismRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @Route("/mecanisme")
 */
class MechanismController extends Controller
{
    /**
     * @Route("/", name="admin_mechanism_index")
     *
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $criteria = $request->get('criteria', array());
        $sorting = $request->get('sorting', array('name' => 'asc'));

        $mechanisms = $this
            ->getRepository()
            ->createPaginator($criteria, $sorting)
            ->setCurrentPage($request->get('page', 1));

        return $this->render('backend/mechanism/index.html.twig', array(
            'mechanisms' => $mechanisms,
        ));
    }

    /**
     * @Route("/new", name="admin_mechanism_new")
     *
     * @param Request $request
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $mechanism = new Mechanism();
        $form = $this->createForm(new MechanismType(), $mechanism);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mechanism);
            $em->flush();

            return $this->redirect($this->generateUrl(
                'admin_mechanism_index',
                array('id' => $mechanism->getId())
            ));
        }

        return $this->render('backend/mechanism/new.html.twig', array(
            'mechanism' => $mechanism,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/edit", name="admin_mechanism_edit")
     *
     * @ParamConverter("mechanism", class="JDJJeuBundle:Mechanism")
     *
     * @param Request $request
     * @param Mechanism $mechanism
     *
     * @return array
     */
    public function updateAction(Request $request, Mechanism $mechanism)
    {
        $form = $this->createForm(new MechanismType(), $mechanism);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mechanism);
            $em->flush();

            return $this->redirect($this->generateUrl(
                'admin_mechanism_index',
                array('id' => $mechanism->getId())
            ));
        }

        return $this->render('backend/mechanism/edit.html.twig', array(
            'mechanism' => $mechanism,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/delete", name="admin_mechanism_delete")
     *
     * @ParamConverter("mechanism", class="JDJJeuBundle:Mechanism")
     *
     * @param Mechanism $mechanism
     *
     * @return RedirectResponse
     */
    public function deleteAction(Mechanism $mechanism)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($mechanism);
        $em->flush();

        return $this->redirect($this->generateUrl(
            'admin_mechanism_index'
        ));
    }

    /**
     * @return MechanismRepository
     */
    public function getRepository()
    {
        return $this->getDoctrine()->getRepository('JDJJeuBundle:Mechanism');
    }
}