<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 22/05/2015
 * Time: 09:05
 */

namespace JDJ\ComptaBundle\Controller;


use Doctrine\Common\Collections\ArrayCollection;
use JDJ\ComptaBundle\Entity\Bill;
use JDJ\ComptaBundle\Entity\BillProduct;
use JDJ\ComptaBundle\Entity\Manager\AddressManager;
use JDJ\ComptaBundle\Entity\Manager\BillManager;
use JDJ\ComptaBundle\Entity\Manager\ProductManager;
use JDJ\ComptaBundle\Entity\Product;
use JDJ\ComptaBundle\Event\BillEvent;
use JDJ\ComptaBundle\Event\BillEvents;
use JDJ\ComptaBundle\Form\BillType;
use JDJ\ComptaBundle\Service\BillProductService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class BillController
 *
 * @Route("/facture")
 *
 */
class BillController extends Controller
{
    /**
     * @return ProductManager
     */
    private function getProductManager()
    {
        return $this->get('app.manager.product');
    }

    /**
     * @return BillManager
     */
    private function getBillManager()
    {
        return $this->get('app.manager.bill');
    }

    /**
     * @return AddressManager
     */
    private function getAddressManager()
    {
        return $this->get('app.manager.address');
    }

    /**
     * Lists all Bill entities.
     *
     * @Route("/", name="compta_bill")
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $bills = $this
            ->getBillManager()
            ->getBillRepository()
            ->createPaginator(null, array('createdAt' => 'desc'))
            ->setCurrentPage($request->get('page', 1));

        $totalPrices = array();

        return $this->render('compta/bill/index.html.twig', array(
            'bills' => $bills,
            'totalPrices' => $totalPrices,
        ));
    }

    /**
     * Finds and displays a Bill entity.
     *
     * @Route("/{id}/show", name="compta_bill_show")
     * @ParamConverter("bill", class="JDJComptaBundle:Bill")
     *
     * @param Bill $bill
     * @return Response
     */
    public function showAction(Bill $bill)
    {
        /** @var BillProduct $billProduct */
        foreach ($bill->getBillProducts() as $billProduct) {
            $this->getProductManager()->revertToVersion($billProduct->getProduct(), $billProduct->getProductVersion());
        }

        return $this->render('compta/bill/show.html.twig', array(
            'bill' => $bill,
            'totalPrice' => $this->getBillManager()->getTotalPrice($bill),
        ));
    }

    /**
     * Displays a form to create a new Bill entity.
     *
     * @Route("/new", name="compta_bill_new")
     *
     * @return Response
     */
    public function newAction()
    {
        $entity = new Bill();
        $form = $this->createCreateForm($entity);

        return $this->render('compta/bill/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new Bill entity.
     *
     * @Route("/create", name="compta_bill_create")
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {

        $bill = new Bill();
        $form = $this->createCreateForm($bill);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /**
             * Save the version of the customer's address
             */
            $customerAddressVersion = $this
                ->getAddressManager()
                ->getCurrentVersion($bill->getCustomer()->getAddress());

            $bill
                ->setCustomerAddressVersion($customerAddressVersion);

            //Set the data of the bill product
            $bill = $this
                ->getBillProductService()
                ->fillBillProduct($bill);

            //Persist data
            $em->persist($bill);
            $em->flush();

            $this->getEventDispatcher()->dispatch(BillEvents::BILL_POST_CREATE, new BillEvent($bill));

            return $this->redirect($this->generateUrl('compta_bill'));
        }

        return $this->render('compta/bill/new.html.twig', array(
            'bill' => $bill,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Bill entity.
     *
     * @param Bill $bill
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Bill $bill)
    {
        $form = $this->createForm(new BillType(), $bill, array(
            'action' => $this->generateUrl('compta_bill_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Créer'));

        return $form;
    }

    /**
     * Displays a form to edit an existing Bill entity.
     *
     * @Route("/{id}/edit", name="compta_bill_edit")
     * @ParamConverter("bill", class="JDJComptaBundle:Bill")
     *
     * @param Bill $bill
     * @return Response
     */
    public function editAction(Bill $bill)
    {
        $form = $this->createEditForm($bill);

        return $this->render('compta/bill/edit.html.twig', array(
            'entity' => $bill,
            'form' => $form->createView(),
        ));
    }

    /**
     * Edits an existing Bill entity.
     *
     * @Route("/{id}/update", name="compta_bill_update")
     * @ParamConverter("bill", class="JDJComptaBundle:Bill")
     *
     * @param Request $request
     * @param Bill $bill
     * @return RedirectResponse|Response
     * @internal param $id
     */
    public function updateAction(Request $request, Bill $bill)
    {
        $em = $this->getDoctrine()->getManager();

        $editForm = $this->createEditForm($bill);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            //Set the data of the bill product
            $bill = $this
                ->getBillProductService()
                ->setProductsBill($bill, $editForm);

            $em->persist($bill);
            $em->flush();

            $this->getEventDispatcher()->dispatch(BillEvents::BILL_POST_UPDATE, new BillEvent($bill));

            return $this->redirect($this->generateUrl('compta_bill'));
        }

        return $this->render('compta/bill/edit.html.twig', array(
            'bill' => $bill,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Bill entity.
     *
     * @param Bill $bill The entity
     * @return Form The form
     */
    private function createEditForm(Bill $bill)
    {
        $form = $this->createForm(new BillType(), $bill, array(
            'action' => $this->generateUrl('compta_bill_update', array('id' => $bill->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }

    /**
     * Displays a form to enrich the payment date of an existing Bill entity.
     *
     * @Route("/{id}/payment/edit", name="compta_bill_payment_edit")
     * @ParamConverter("bill", class="JDJComptaBundle:Bill")
     *
     * @param Bill $bill
     * @return Response
     */
    public function paymentEditAction(Bill $bill)
    {
        $form = $this->createPaymentEditForm($bill);

        return $this->render('compta/bill/payment/edit.html.twig', array(
            'bill' => $bill,
            'form' => $form->createView(),
        ));
    }

    /**
     * Edits an existing Bill entity to enrich the payment date.
     *
     * @Route("/{id}/payment/update", name="compta_bill_payement_update")
     * @ParamConverter("bill", class="JDJComptaBundle:Bill")
     *
     * @param Request $request
     * @param Bill $bill
     * @return RedirectResponse|Response
     * @internal param $id
     */
    public function paymentUpdateAction(Request $request, Bill $bill)
    {
        $em = $this->getDoctrine()->getManager();

        $editForm = $this->createPaymentEditForm($bill);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->getEventDispatcher()->dispatch(BillEvents::BILL_PAID, new GenericEvent($bill));
            return $this->redirect($this->generateUrl('compta_bill'));
        }

        return $this->render('compta/bill/payment/edit.html.twig', array(
            'bill' => $bill,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Get event dispatcher.
     *
     * @return EventDispatcherInterface
     */
    private function getEventDispatcher()
    {
        return $this->get('event_dispatcher');
    }

    /**
     * Creates a form to edit a Bill entity.
     *
     * @param Bill $bill The entity
     * @return Form The form
     */
    private function createPaymentEditForm(Bill $bill)
    {
        $form = $this->createForm(new BillType(), $bill, array(
            'action' => $this->generateUrl('compta_bill_payment_update', array('id' => $bill->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }

    /**
     * Deletes a Bill entity.
     *
     * @Route("/{id}/delete", name="compta_bill_delete")
     * @ParamConverter("bill", class="JDJComptaBundle:Bill")
     *
     * @param Bill $bill
     * @return RedirectResponse
     */
    public function deleteAction(Bill $bill)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($bill);
        $em->flush();

        return $this->redirect($this->generateUrl('compta_bill'));
    }

    /**
     * @return BillProductService
     */
    private function getBillProductService()
    {
        return $this->get("app.service.bill.product");
    }
}