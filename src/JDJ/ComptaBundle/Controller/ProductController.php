<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 21/05/2015
 * Time: 11:00
 */

namespace JDJ\ComptaBundle\Controller;

use JDJ\ComptaBundle\Entity\Product;
use JDJ\ComptaBundle\Form\ProductType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 *
 * @Route("/produit")
 */
class ProductController extends Controller
{
    /**
     * Lists all Product entities.
     *
     * @Route("/", name="compta_product")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('JDJComptaBundle:Product')->findAll();

        return $this->render('compta/product/index.html.twig', array(
            'products' => $products,
        ));
    }

    /**
     * Displays a form to create a new Product entity.
     *
     * @Route("/new", name="compta_product_new")
     *
     * @return Response
     */
    public function newAction()
    {
        $product = new Product();
        $form   = $this->createCreateForm($product);

        return $this->render('compta/product/new.html.twig', array(
            'product' => $product,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Product entity.
     *
     * @Route("/create", name="compta_product_create")
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $product = new Product();
        $form = $this->createCreateForm($product);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirect($this->generateUrl('compta_product'));
        }

        return $this->render('compta/product/new.html.twig', array(
            'product' => $product,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Product entity.
     *
     * @param Product $product
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Product $product)
    {
        $form = $this->createForm(new ProductType(), $product, array(
            'action' => $this->generateUrl('compta_product_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to edit an existing Product entity.
     *
     * @Route("/{product}/edit", name="compta_product_edit")
     * @ParamConverter("product", class="JDJComptaBundle:Product")
     *
     * @param Product $product
     * @return Response
     */
    public function editAction(Product $product)
    {
        $form = $this->createEditForm($product);

        return $this->render('compta/product/edit.html.twig', array(
            'entity'      => $product,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Edits an existing Product entity.
     *
     * @Route("/{product}/update", name="compta_product_update")
     * @ParamConverter("product", class="JDJComptaBundle:Product")
     *
     * @param Request $request
     * @param Product $product
     * @return RedirectResponse|Response
     * @internal param $id
     */
    public function updateAction(Request $request, Product $product)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createEditForm($product);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('compta_product'));
        }

        return $this->render('compta/product/edit.html.twig', array(
            'product'      => $product,
            'edit_form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to edit a Product entity.
     *
     * @param Product $product The entity
     * @return Form The form
     */
    private function createEditForm(Product $product)
    {
        $form = $this->createForm(new ProductType(), $product, array(
            'action' => $this->generateUrl('compta_product_update', array('product' => $product->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }

    /**
     * Deletes a Product entity.
     *
     * @Route("/{id}/delete", name="compta_product_delete")
     * @Method({"DELETE"})
     * @ParamConverter("product", class="JDJComptaBundle:Product")
     *
     * @param Product $product
     *
     * @return RedirectResponse
     */
    public function deleteAction(Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return $this->redirect($this->generateUrl('compta_product'));
    }
}
