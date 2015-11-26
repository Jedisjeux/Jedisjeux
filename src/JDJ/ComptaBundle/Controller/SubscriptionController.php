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
        $subscriptions = $this
            ->getSubscriptionRepository()
            ->createPaginator($request->get('criteria', array()), $request->get('sorting', array(
                'createdAt' => 'desc'
            )))
            ->setCurrentPage($request->get('page', 1));

        return $this->render('compta/subscription/index.html.twig', array(
            'subscriptions' => $subscriptions,
        ));
    }

    /**
     * @Route("/{id}/start", name="compta_subscription_start")
     * @ParamConverter("subscription", class="JDJComptaBundle:Subscription")
     *
     * @param Subscription $subscription
     * @return RedirectResponse
     */
    public function startAction(Subscription $subscription)
    {
        $em = $this->getDoctrine()->getManager();

        $subscription
            ->setStartAt(new \DateTime())
            ->setEndAt($this->getSubscriptionManager()->calculateEndingDate($subscription))
            ->setStatus(Subscription::IN_PROGRESS);

        $em->flush();

        return $this->redirect($this->generateUrl('compta_subscription'));
    }
}