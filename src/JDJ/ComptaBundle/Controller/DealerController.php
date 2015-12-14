<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 14/12/2015
 * Time: 11:42
 */

namespace JDJ\ComptaBundle\Controller;

use JDJ\ComptaBundle\Entity\Dealer;
use JDJ\ComptaBundle\Entity\Repository\DealerRepository;
use JDJ\ComptaBundle\Form\Type\DealerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @Route("/distributeur")
 */
class DealerController extends Controller
{
    /**
     * @return DealerRepository
     */
    protected function getDealerRepository()
    {
        return $this
            ->getDoctrine()
            ->getRepository('JDJComptaBundle:Dealer');
    }

    /**
     * Lists all Dealer entities.
     *
     * @Route("/", name="compta_dealer")
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $dealers = $this
            ->getDealerRepository()
            ->createPaginator($request->get('criteria', array()), $request->get('sorting', array('name' => 'asc')))
            ->setCurrentPage($request->get('page', 1));

        return $this->render('compta/dealer/index.html.twig', array(
            'dealers' => $dealers,
        ));
    }

    /**
     * Creates a new Dealer entity.
     *
     * @Route("/new", name="compta_dealer_create")
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dealer = new Dealer();

        $form = $this->createForm(new DealerType(), $dealer, array(
            'action' => $this->generateUrl('compta_dealer_create', array('id' => $dealer->getId())),
            'method' => 'PUT',
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em->persist($dealer);
            $em->flush();

            return $this->redirect($this->generateUrl('compta_dealer'));
        }

        return $this->render('compta/dealer/new.html.twig', array(
            'dealer' => $dealer,
            'form' => $form->createView(),
        ));
    }

    /**
     * Edits an existing Dealer entity.
     *
     * @Route("/{id}/update", name="compta_dealer_update")
     * @ParamConverter("dealer", class="JDJComptaBundle:Dealer")
     *
     * @param Request $request
     * @param Dealer $dealer
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, Dealer $dealer)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new DealerType(), $dealer, array(
            'action' => $this->generateUrl('compta_dealer_update', array('id' => $dealer->getId())),
            'method' => 'PUT',
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em->persist($dealer);
            $em->flush();

            return $this->redirect($this->generateUrl('compta_dealer'));
        }

        return $this->render('compta/dealer/edit.html.twig', array(
            'dealer' => $dealer,
            'form' => $form->createView(),
        ));
    }

    /**
     * Deletes a Dealer entity.
     *
     * @Route("/{id}/delete", name="compta_dealer_delete")
     * @ParamConverter("dealer", class="JDJComptaBundle:Dealer")
     *
     * @param Dealer $dealer
     * @return RedirectResponse
     */
    public function deleteAction(Dealer $dealer)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($dealer);
        $em->flush();

        return $this->redirect($this->generateUrl('compta_dealer'));
    }
}