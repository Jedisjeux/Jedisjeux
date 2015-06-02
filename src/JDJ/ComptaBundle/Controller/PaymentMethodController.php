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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BookEntryController
 *
 * @Route("/payement-method")
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

        $deleteForms = array();

        /** @var PaymentMethod $paymentMethod */
        foreach ($paymentMethods as $paymentMethod) {
            $deleteForms[$paymentMethod->getId()] = $this->createDeleteForm($paymentMethod->getId())->createView();
        }

        return $this->render('compta/payment-method/index.html.twig', array(
            'paymentMethods' => $paymentMethods,
            'deleteForms' => $deleteForms,
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

        $form->add('submit', 'submit', array('label' => 'Créer'));

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
     * Creates a form to edit a PaymentMethod entity.
     *
     * @param PaymentMethod $paymentMethod The entity
     * @return Form The form
     */
    private function createEditForm(PaymentMethod $paymentMethod)
    {
        $form = $this->createForm(new PayementMethodType(), $paymentMethod, array(
            'action' => $this->generateUrl('compta_payment_method_update', array('paymentMethod' => $paymentMethod->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }

    /**
     * Deletes a PaymentMethod entity.
     *
     * @Route("/{paymentMethod}/delete", name="compta_payment_method_delete")
     * @ParamConverter("paymentMethod", class="JDJComptaBundle:PaymentMethod")
     *
     * @param Request $request
     * @param PaymentMethod $paymentMethod
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, PaymentMethod $paymentMethod)
    {
        $form = $this->createDeleteForm($paymentMethod->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($paymentMethod);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('compta_book_entry'));
    }

    /**
     * Creates a form to delete a PaymentMethod entity by id.
     *
     * @param int $id The entity id
     * @return Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('compta_payment_method_delete', array('paymentMethod' => $id)))
            ->setMethod('DELETE')
            ->getForm();
    }
}