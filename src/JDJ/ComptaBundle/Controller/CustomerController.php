<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 21/05/2015
 * Time: 11:00
 */

namespace JDJ\ComptaBundle\Controller;

use JDJ\ComptaBundle\Entity\Customer;
use JDJ\ComptaBundle\Entity\Repository\CustomerRepository;
use JDJ\ComptaBundle\Form\CustomerType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 *
 * @Route("/client")
 */
class CustomerController extends Controller
{
    /**
     * @return CustomerRepository
     */
    private function getCustomerRepository()
    {
        return $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('JDJComptaBundle:Customer');
    }

    /**
     * Lists all Customer entities.
     *
     * @Route("/", name="compta_customer")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $customers = $this
            ->getCustomerRepository()
            ->createPaginator()
            ->setCurrentPage($request->get('page', 1));

        $deleteForms = array();

        /** @var Customer $customer */
        foreach ($customers as $customer) {
            $deleteForms[$customer->getId()] = $this->createDeleteForm($customer->getId())->createView();
        }

        return $this->render('compta/customer/index.html.twig', array(
            'customers' => $customers,
            'deleteForms' => $deleteForms,
        ));
    }

    /**
     * Displays a form to create a new Customer entity.
     *
     * @Route("/new", name="compta_customer_new")
     *
     * @return Response
     */
    public function newAction()
    {
        $entity = new Customer();
        $form   = $this->createCreateForm($entity);

        return $this->render('compta/customer/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Customer entity.
     *
     * @Route("/create", name="compta_customer_create")
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $customer = new Customer();
        $form = $this->createCreateForm($customer);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush();

            return $this->redirect($this->generateUrl('compta_customer'));
        }

        return $this->render('compta/customer/new.html.twig', array(
            'customer' => $customer,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Customer entity.
     *
     * @param Customer $customer
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Customer $customer)
    {
        $form = $this->createForm(new CustomerType(), $customer, array(
            'action' => $this->generateUrl('compta_customer_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to edit an existing Customer entity.
     *
     * @Route("/{customer}/edit", name="compta_customer_edit")
     * @ParamConverter("customer", class="JDJComptaBundle:Customer")
     *
     * @param Customer $customer
     * @return Response
     */
    public function editAction(Customer $customer)
    {
        $form = $this->createEditForm($customer);

        return $this->render('compta/customer/edit.html.twig', array(
            'entity'      => $customer,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Edits an existing Customer entity.
     *
     * @Route("/{customer}/update", name="compta_customer_update")
     * @ParamConverter("customer", class="JDJComptaBundle:Customer")
     *
     * @param Request $request
     * @param Customer $customer
     * @return RedirectResponse|Response
     * @internal param $id
     */
    public function updateAction(Request $request, Customer $customer)
    {
        $em = $this->getDoctrine()->getManager();

        $deleteForm = $this->createDeleteForm($customer->getId());
        $editForm = $this->createEditForm($customer);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('compta_customer'));
        }

        return $this->render('compta/customer/edit.html.twig', array(
            'customer'      => $customer,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Customer entity.
     *
     * @param Customer $customer The entity
     * @return Form The form
     */
    private function createEditForm(Customer $customer)
    {
        $form = $this->createForm(new CustomerType(), $customer, array(
            'action' => $this->generateUrl('compta_customer_update', array('customer' => $customer->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }

    /**
     * Deletes a Customer entity.
     *
     * @Route("/{customer}/delete", name="compta_customer_delete")
     * @ParamConverter("customer", class="JDJComptaBundle:Customer")
     *
     * @param Request $request
     * @param Customer $customer
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Customer $customer)
    {
        $form = $this->createDeleteForm($customer->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($customer);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('compta_customer'));
    }

    /**
     * Creates a form to delete a Customer entity by id.
     *
     * @param int $id The entity id
     * @return Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('compta_customer_delete', array('customer' => $id)))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * Finds and displays a Customer entity.
     *
     * @Route("/{customer}/show", name="compta_customer_show")
     * @ParamConverter("customer", class="JDJComptaBundle:Customer")
     *
     * @param Customer $customer
     * @return Response
     */
    public function showAction(Customer $customer)
    {
        $deleteForm = $this->createDeleteForm($customer->getId())->createView();

        return $this->render('compta/customer/show.html.twig', array(
            'customer' => $customer,
            'deleteForm' => $deleteForm,
        ));
    }
}