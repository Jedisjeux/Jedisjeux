<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 22/05/2015
 * Time: 09:05
 */

namespace JDJ\ComptaBundle\Controller;


use JDJ\ComptaBundle\Entity\Bill;
use JDJ\ComptaBundle\Entity\BillProduct;
use JDJ\ComptaBundle\Entity\Manager\AddressManager;
use JDJ\ComptaBundle\Entity\Manager\BillManager;
use JDJ\ComptaBundle\Entity\Manager\ProductManager;
use JDJ\ComptaBundle\Entity\Product;
use JDJ\ComptaBundle\Event\BillEvent;
use JDJ\ComptaBundle\Event\BillEvents;
use JDJ\ComptaBundle\Form\BillType;
use JDJ\ComptaBundle\Form\Type\Bill\PaymentType;
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

/**
 * Class BillController
 *
 * @Route("/facture")
 *
 */
class BillController extends Controller
{
    /**
     * @return BillManager
     */
    private function getBillManager()
    {
        return $this->get('app.manager.bill');
    }

    /**
     * @return ProductManager
     */
    private function getProductManager()
    {
        return $this->get('app.manager.product');
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
        /** @var Bill[] $bills */
        $bills = $this
            ->getBillManager()
            ->getBillRepository()
            ->createPaginator($request->get('criteria', array()), $request->get('sorting', array('createdAt' => 'desc')))
            ->setCurrentPage($request->get('page', 1));

        foreach ($bills as $bill) {
            $this->getBillManager()->calculateTotalPrice($bill);
        }

        return $this->render('compta/bill/index.html.twig', array(
            'bills' => $bills,
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
        $this->getBillManager()->calculateTotalPrice($bill);

//        return $this->render('compta/bill/show.html.twig', array(
//            'bill' => $bill,
//        ));

        $html = $this->renderView('compta/bill/show.html.twig', array(
            'bill'  => $bill
        ));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => sprintf('attachment; filename="facture_%s.pdf"', $bill->getId())
            )
        );
    }

    /**
     * Creates a new Bill entity.
     *
     * @Route("/new", name="compta_bill_create")
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $bill = new Bill();

        /** @var Product[] $products */
        $products = $this->getProductManager()->getProductRepository()->findAll();
        foreach ($products as $product) {
            $billProduct = new BillProduct();
            $billProduct
                ->setProduct($product)
                ->setQuantity(1);
            $bill->addBillProduct($billProduct);
        }

        $form = $this->createForm(new BillType(), $bill, array(
            'action' => $this->generateUrl('compta_bill_create'),
            'method' => 'POST',
        ));

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

            $this->getEventDispatcher()->dispatch(BillEvents::POST_CREATE, new BillEvent($bill));

            return $this->redirect($this->generateUrl('compta_bill'));
        }

        return $this->render('compta/bill/new.html.twig', array(
            'bill' => $bill,
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

        $form = $this->createForm(new BillType(), $bill, array(
            'action' => $this->generateUrl('compta_bill_update', array('id' => $bill->getId())),
            'method' => 'PUT',
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {

            //Set the data of the bill product
            $bill = $this
                ->getBillProductService()
                ->setProductsBill($bill, $form);

            $em->persist($bill);
            $em->flush();

            $this->getEventDispatcher()->dispatch(BillEvents::POST_UPDATE, new BillEvent($bill));

            return $this->redirect($this->generateUrl('compta_bill'));
        }

        return $this->render('compta/bill/edit.html.twig', array(
            'bill' => $bill,
            'form' => $form->createView(),
        ));
    }

    /**
     * Edits an existing Bill entity to enrich the payment date.
     *
     * @Route("/{id}/payment/update", name="compta_bill_payment_update")
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

        $form = $this->createForm(new PaymentType(), $bill, array(
            'action' => $this->generateUrl('compta_bill_payment_update', array('id' => $bill->getId())),
            'method' => 'PUT',
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getEventDispatcher()->dispatch(BillEvents::PRE_PAID, new BillEvent($bill));
            $em->flush();
            return $this->redirect($this->generateUrl('compta_bill'));
        }

        return $this->render('compta/bill/payment/edit.html.twig', array(
            'bill' => $bill,
            'form' => $form->createView(),
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