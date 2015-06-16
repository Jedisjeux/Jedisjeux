<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 05/06/2015
 * Time: 09:36
 */

namespace JDJ\ComptaBundle\Controller;


use JDJ\ComptaBundle\Entity\Bill;
use JDJ\ComptaBundle\Entity\Manager\SubscriptionManager;
use JDJ\ComptaBundle\Entity\Product;
use JDJ\ComptaBundle\Entity\Repository\SubscriptionRepository;
use JDJ\ComptaBundle\Entity\Subscription;
use JDJ\ComptaBundle\Form\SubscriptionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 *
 * @Route("/abonnement")
 */
class SubscriptionController extends Controller
{
    /**
     * @return SubscriptionRepository
     */
    private function getSubscriptionRepository()
    {
        return $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('JDJComptaBundle:Subscription');
    }

    /**
     * @return SubscriptionManager
     */
    private function getSubscriptionManager()
    {
        return $this->get('app.manager.subscription');
    }

    /**
     * Lists all current Subscription entities.
     *
     * @Route("/", name="compta_subscription")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $subscriptions = $this->getSubscriptionRepository()->createPaginator(array(
            'toBeRenewed' => true,
        ), array(
            array('endAt' => 'asc')
        ))
        ->setCurrentPage($request->get('page', 1));

        return $this->render('compta/subscription/index.html.twig', array(
            'subscriptions' => $subscriptions,
        ));
    }

    /**
     * Displays a form to create a new Subscription entity.
     *
     * @Route("/bill/{bill}/product/{product}/new", name="compta_subscription_new")
     * @ParamConverter("bill", class="JDJComptaBundle:Bill")
     * @ParamConverter("product", class="JDJComptaBundle:Product")
     *
     * @param Bill $bill
     * @param Product $product
     * @return Response
     */
    public function newAction(Bill $bill, Product $product)
    {
        $subscription = new Subscription();
        $subscription
            ->setBill($bill)
            ->setProduct($product);
        $form   = $this->createCreateForm($subscription);

        return $this->render('compta/subscription/new.html.twig', array(
            'subscription' => $subscription,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Subscription entity.
     *
     * @Route("/create", name="compta_product_create")
     * @ParamConverter("bill", class="JDJComptaBundle:Bill")
     * @ParamConverter("product", class="JDJComptaBundle:Product")
     *
     * @param Request $request
     * @param Bill $bill
     * @param Product $product
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request, Bill $bill, Product $product)
    {
        $subscription = new Subscription();
        $subscription
            ->setBill($bill)
            ->setProduct($product);
        $form = $this->createCreateForm($subscription);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this
                ->getSubscriptionManager()
                ->endRenewalByProductAndCustomer($subscription->getProduct(), $subscription->getBill()->getCustomer());
            $endAt = $this
                ->getSubscriptionManager()
                ->calculateEndingDate($subscription);
            $subscription->setEndAt($endAt);
            $em = $this->getDoctrine()->getManager();
            $em->persist($subscription);
            $em->flush();

            return $this->redirect($this->generateUrl('compta_subscription'));
        }

        return $this->render('compta/subscription/new.html.twig', array(
            'subscription' => $subscription,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Subscription entity.
     *
     * @param Subscription $subscription
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Subscription $subscription)
    {
        $form = $this->createForm(new SubscriptionType(), $subscription, array(
            'action' => $this->generateUrl('compta_subscription_create', array(
                'bill' => $subscription->getBill()->getId(),
                'product' => $subscription->getProduct()->getId(),
            )),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Créer'));

        return $form;
    }
}