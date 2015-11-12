<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 02/06/2015
 * Time: 10:17
 */

namespace JDJ\ComptaBundle\Controller;

use JDJ\ComptaBundle\Entity\PaymentMethod;
use JDJ\ComptaBundle\Form\PaymentMethodType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BookEntryController
 *
 * @Route("/mode-paiement")
 */
class PaymentMethodController extends Controller
{
    /**
     * Lists all BookEntry entities.
     *
     * @Route("/", name="compta_payment_method")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $paymentMethods = $em->getRepository('JDJComptaBundle:PaymentMethod')->findAll();

        return $this->render('compta/payment-method/index.html.twig', array(
            'paymentMethods' => $paymentMethods,
        ));
    }

    /**
     * Displays a form to create a new PaymentMethod entity.
     *
     * @Route("/new", name="compta_payment_method_new")
     *
     * @return Response
     */
    public function newAction()
    {
        $paymentMethod = new PaymentMethod();
        $form = $this->createCreateForm($paymentMethod);

        return $this->render('compta/payment-method/new.html.twig', array(
            'paymentMethod' => $paymentMethod,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new PaymentMethod entity.
     *
     * @Route("/create", name="compta_payment_method_create")
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $paymentMethod = new PaymentMethod();
        $form = $this->createCreateForm($paymentMethod);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($paymentMethod);
            $em->flush();

            return $this->redirect($this->generateUrl('compta_payment_method'));
        }

        return $this->render('compta/payment-method/new.html.twig', array(
            'paymentMethod' => $paymentMethod,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a PaymentMethod entity.
     *
     * @param PaymentMethod $paymentMethod
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(PaymentMethod $paymentMethod)
    {
        $form = $this->createForm(new PaymentMethodType(), $paymentMethod, array(
            'action' => $this->generateUrl('compta_payment_method_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to edit an existing PaymentMethod entity.
     *
     * @Route("/{paymentMethod}/edit", name="compta_payment_method_edit")
     * @ParamConverter("paymentMethod", class="JDJComptaBundle:PaymentMethod")
     *
     * @param PaymentMethod $paymentMethod
     * @return Response
     */
    public function editAction(PaymentMethod $paymentMethod)
    {
        $form = $this->createEditForm($paymentMethod);

        return $this->render('compta/payment-method/edit.html.twig', array(
            'paymentMethod' => $paymentMethod,
            'form' => $form->createView(),
        ));
    }

    /**
     * Edits an existing PaymentMethod entity.
     *
     * @Route("/{paymentMethod}/update", name="compta_payment_method_update")
     * @ParamConverter("paymentMethod", class="JDJComptaBundle:PaymentMethod")
     *
     * @param Request $request
     * @param PaymentMethod $paymentMethod
     * @return RedirectResponse|Response
     * @internal param $id
     */
    public function updateAction(Request $request, PaymentMethod $paymentMethod)
    {
        $em = $this->getDoctrine()->getManager();

        $editForm = $this->createEditForm($paymentMethod);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('compta_payment_method'));
        }

        return $this->render('compta/payment-method/edit.html.twig', array(
            'paymentMethod'      => $paymentMethod,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a PaymentMethod entity.
     *
     * @param PaymentMethod $paymentMethod The entity
     * @return Form The form
     */
    private function createEditForm(PaymentMethod $paymentMethod)
    {
        $form = $this->createForm(new PaymentMethodType(), $paymentMethod, array(
            'action' => $this->generateUrl('compta_payment_method_update', array('paymentMethod' => $paymentMethod->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }

    /**
     * Deletes a PaymentMethod entity.
     *
     * @Route("/{paymentMethod}/delete", name="compta_payment_method_delete")
     * @Method({"DELETE"})
     * @ParamConverter("paymentMethod", class="JDJComptaBundle:PaymentMethod")
     *
     * @param PaymentMethod $paymentMethod
     * @return RedirectResponse
     */
    public function deleteAction(PaymentMethod $paymentMethod)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($paymentMethod);
        $em->flush();

        return $this->redirect($this->generateUrl('compta_payment_method'));
    }
}