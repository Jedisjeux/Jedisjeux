<?php

namespace JDJ\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormInterface;

/**
 * Backend forms controller.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 * @author Saša Stamenković <umpirsky@gmail.com>
 */
class FormController extends Controller
{
    /**
     * @param string $type
     * @param string $placeholder
     * @param string $template
     *
     * @return Response
     */
    public function showAction($type, $placeholder, $template)
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
    public function filterAction( $type, $placeholder, $template = 'form:_filter.html.twig', array $criteria = null)
    {
        /** @var Form $form */
        $form = $this->get('form.factory')->createNamed('criteria', $type);

        if (is_array($criteria)) {
            /** @var FormInterface $child */
            foreach($form->all() as $name => $child) {
                if (array_key_exists($name, $criteria)) {
                    $value = $criteria[$name];

                    if ($child->getConfig()->getType()->getName()== 'checkbox') {
                        $value= true;
                    }

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
