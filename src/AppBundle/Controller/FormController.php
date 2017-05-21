<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class FormController extends Controller
{
    /**
     * @param Request $request
     * @param string $type
     * @param string $placeholder
     * @param string $template
     *
     * @param null $criteria
     * @return Response
     */
    public function showAction(Request $request, $type, $placeholder, $template, $criteria = null)
    {
        return $this->render($template, array(
            'form' => $this->createForm($type)->createView(),
            'placeholder' => $placeholder
        ));
    }

    /**
     * Render filter form.
     *
     * @param string $type
     * @param string $placeholder
     * @param string $template
     *
     * @param null|array $criteria
     * @return Response
     */
    public function filterAction($type, $placeholder, $template = 'backend/form/_filterForm.html.twig', array $criteria = null)
    {
        /** @var FormBuilderInterface $form */
        $form = $this->get('form.factory')->createNamed('criteria', $type);

        if (is_array($criteria)) {
            /** @var FormInterface $child */
            foreach ($form->all() as $name => $child) {
                if (array_key_exists($name, $criteria)) {
                    $value = $criteria[$name];

                    $child->setData($value);
                }
            }
        }

        return $this->render($template, array(
            'form' => $form->createView(),
            'placeholder' => $placeholder
        ));
    }
}
