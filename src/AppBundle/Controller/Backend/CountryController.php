<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 23/12/2015
 * Time: 10:04
 */

namespace AppBundle\Controller\Backend;

use AppBundle\Entity\Country;
use AppBundle\Form\Type\CountryType;
use JDJ\CoreBundle\Entity\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @Route("/pays")
 */
class CountryController extends Controller
{
    /**
     * @Route("/", name="admin_country_index")
     *
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $criteria = $request->get('criteria', array());
        $sorting = $request->get('sorting', array('name' => 'asc'));

        $countries = $this
            ->getRepository()
            ->createPaginator($criteria, $sorting)
            ->setCurrentPage($request->get('page', 1));

        return $this->render('backend/country/index.html.twig', array(
            'countries' => $countries,
        ));
    }

    /**
     * @Route("/new", name="admin_country_new")
     *
     * @param Request $request
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $country = new Country();
        $form = $this->createForm(new CountryType(), $country);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($country);
            $em->flush();

            return $this->redirect($this->generateUrl(
                'admin_country_index',
                array('id' => $country->getId())
            ));
        }

        return $this->render('backend/country/new.html.twig', array(
            'country' => $country,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/edit", name="admin_country_edit")
     *
     * @ParamConverter("country", class="JDJJeuBundle:Country")
     *
     * @param Request $request
     * @param Country $country
     *
     * @return array
     */
    public function updateAction(Request $request, Country $country)
    {
        $form = $this->createForm(new CountryType(), $country);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($country);
            $em->flush();

            return $this->redirect($this->generateUrl(
                'admin_country_index',
                array('id' => $country->getId())
            ));
        }

        return $this->render('backend/country/edit.html.twig', array(
            'country' => $country,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/delete", name="admin_country_delete")
     *
     * @ParamConverter("country", class="JDJJeuBundle:Country")
     *
     * @param Country $country
     *
     * @return RedirectResponse
     */
    public function deleteAction(Country $country)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($country);
        $em->flush();

        return $this->redirect($this->generateUrl(
            'admin_country_index'
        ));
    }

    /**
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->getDoctrine()->getRepository('AppBundle:Country');
    }
}