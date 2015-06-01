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
use JDJ\ComptaBundle\Entity\Manager\BillManager;
use JDJ\ComptaBundle\Entity\Manager\ProductManager;
use JDJ\ComptaBundle\Entity\Product;
use JDJ\ComptaBundle\Form\BillType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BillController
 *
 * @Route("/bill")
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
     * Lists all Bill entities.
     *
     * @Route("/", name="compta_bill")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $bills = $em->getRepository('JDJComptaBundle:Bill')->findAll();

        $deleteForms = array();
        $totalPrices = array();

        /** @var Bill $bill */
        foreach ($bills as $bill) {
            $deleteForms[$bill->getId()] = $this->createDeleteForm($bill->getId())->createView();
            $totalPrices[$bill->getId()] = $this->getBillManager()->getTotalPrice($bill);
        }

        return $this->render('compta/bill/index.html.twig', array(
            'bills' => $bills,
            'deleteForms' => $deleteForms,
            'totalPrices' => $totalPrices,
        ));
    }

    /**
     * Finds and displays a Bill entity.
     *
     * @Route("/{bill}/show", name="compta_bill_show")
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
        $form   = $this->createCreateForm($entity);

        return $this->render('compta/bill/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
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
            $em->persist($bill);

            $products = $form->get('products')->getData();

            /** @var Product $product */
            foreach ($products as $product) {

                $currentVersion = $this
                    ->getProductManager()
                    ->getCurrentVersion($product);

                $billProduct = new BillProduct();
                $billProduct
                    ->setProduct($product)
                    ->setProductVersion($currentVersion)
                    ->setBill($bill)
                    ->setQuantity(1);

                $bill->addBillProduct($billProduct);
            }

            $em->flush();

            return $this->redirect($this->generateUrl('compta_bill'));
        }

        return $this->render('compta/bill/new.html.twig', array(
            'bill' => $bill,
            'form'   => $form->createView(),
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
     * @Route("/{bill}/edit", name="compta_bill_edit")
     * @ParamConverter("bill", class="JDJComptaBundle:Bill")
     *
     * @param Bill $bill
     * @return Response
     */
    public function editAction(Bill $bill)
    {
        $form = $this->createEditForm($bill);

        return $this->render('compta/bill/edit.html.twig', array(
            'entity'      => $bill,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Edits an existing Bill entity.
     *
     * @Route("/{bill}/update", name="compta_bill_update")
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

        $deleteForm = $this->createDeleteForm($bill->getId());
        $editForm = $this->createEditForm($bill);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('compta_bill'));
        }

        return $this->render('compta/bill/edit.html.twig', array(
            'bill'      => $bill,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
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
            'action' => $this->generateUrl('compta_bill_update', array('bill' => $bill->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }

    /**
     * Deletes a Bill entity.
     *
     * @Route("/{bill}/delete", name="compta_bill_delete")
     * @ParamConverter("bill", class="JDJComptaBundle:Bill")
     *
     * @param Request $request
     * @param Bill $bill
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Bill $bill)
    {
        $form = $this->createDeleteForm($bill->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($bill);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('compta_bill'));
    }

    /**
     * Creates a form to delete a Bill entity by id.
     *
     * @param int $id The entity id
     * @return Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('compta_bill_delete', array('bill' => $id)))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}