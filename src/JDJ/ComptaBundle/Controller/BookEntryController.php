<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 26/05/2015
 * Time: 16:03
 */

namespace JDJ\ComptaBundle\Controller;

use JDJ\ComptaBundle\Entity\BookEntry;
use JDJ\ComptaBundle\Entity\Repository\BookEntryRepository;
use JDJ\ComptaBundle\Form\BookEntryType;
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
 * @Route("/ecriture")
 */
class BookEntryController extends Controller
{
    /**
     * @return BookEntryRepository
     */
    private function getBookEntryRepository()
    {
        return $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('JDJComptaBundle:BookEntry');
    }

    /**
     * Lists all BookEntry entities.
     *
     * @Route("/", name="compta_book_entry")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $bookEntries = $this
            ->getBookEntryRepository()
            ->createPaginator(null, array('createdAt' => 'desc'))
            ->setCurrentPage($request->get('page', 1));

        $deleteForms = array();

        /** @var BookEntry $bookEntry */
        foreach ($bookEntries as $bookEntry) {
            $deleteForms[$bookEntry->getId()] = $this->createDeleteForm($bookEntry->getId())->createView();
        }

        return $this->render('compta/book-entry/index.html.twig', array(
            'bookEntries' => $bookEntries,
            'deleteForms' => $deleteForms,
        ));
    }

    /**
     * Displays a form to create a new BookEntry entity.
     *
     * @Route("/new", name="compta_book_entry_new")
     *
     * @return Response
     */
    public function newAction()
    {
        $bookEntry = new BookEntry();
        $form = $this->createCreateForm($bookEntry);

        return $this->render('compta/book-entry/new.html.twig', array(
            'bookEntry' => $bookEntry,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new BookEntry entity.
     *
     * @Route("/create", name="compta_book_entry_create")
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $bookEntry = new BookEntry();
        $form = $this->createCreateForm($bookEntry);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bookEntry);
            $em->flush();

            return $this->redirect($this->generateUrl('compta_book_entry'));
        }

        return $this->render('compta/book-entry/new.html.twig', array(
            'bookEntry' => $bookEntry,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a BookEntry entity.
     *
     * @param BookEntry $bookEntry
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(BookEntry $bookEntry)
    {
        $form = $this->createForm(new BookEntryType(), $bookEntry, array(
            'action' => $this->generateUrl('compta_book_entry_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Créer'));

        return $form;
    }

    /**
     * Displays a form to edit an existing BookEntry entity.
     *
     * @Route("/{bookEntry}/edit", name="compta_book_entry_edit")
     * @ParamConverter("bookEntry", class="JDJComptaBundle:BookEntry")
     *
     * @param BookEntry $bookEntry
     * @return Response
     */
    public function editAction(BookEntry $bookEntry)
    {
        $form = $this->createEditForm($bookEntry);

        return $this->render('compta/book-entry/edit.html.twig', array(
            'bookEntry' => $bookEntry,
            'form' => $form->createView(),
        ));
    }

    /**
     * Edits an existing BookEntry entity.
     *
     * @Route("/{bookEntry}/update", name="compta_book_entry_update")
     * @ParamConverter("bookEntry", class="JDJComptaBundle:BookEntry")
     *
     * @param Request $request
     * @param BookEntry $bookEntry
     * @return RedirectResponse|Response
     * @internal param $id
     */
    public function updateAction(Request $request, BookEntry $bookEntry)
    {
        $em = $this->getDoctrine()->getManager();

        $deleteForm = $this->createDeleteForm($bookEntry->getId());
        $editForm = $this->createEditForm($bookEntry);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('compta_book_entry'));
        }

        return $this->render('compta/book-entry/edit.html.twig', array(
            'bookEntry' => $bookEntry,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a BookEntry entity.
     *
     * @param BookEntry $bookEntry The entity
     * @return Form The form
     */
    private function createEditForm(BookEntry $bookEntry)
    {
        $form = $this->createForm(new BookEntryType(), $bookEntry, array(
            'action' => $this->generateUrl('compta_book_entry_update', array('bookEntry' => $bookEntry->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }

    /**
     * Deletes a BookEntry entity.
     *
     * @Route("/{bookEntry}/delete", name="compta_book_entry_delete")
     * @ParamConverter("bookEntry", class="JDJComptaBundle:BookEntry")
     *
     * @param Request $request
     * @param BookEntry $bookEntry
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, BookEntry $bookEntry)
    {
        $form = $this->createDeleteForm($bookEntry->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($bookEntry);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('compta_book_entry'));
    }

    /**
     * Creates a form to delete a BookEntry entity by id.
     *
     * @param int $id The entity id
     * @return Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('compta_book_entry_delete', array('bookEntry' => $id)))
            ->setMethod('DELETE')
            ->getForm();
    }
}