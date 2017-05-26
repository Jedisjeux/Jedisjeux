<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type\User;

use Sylius\Bundle\UserBundle\Form\Type\UserType;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class AppUserType extends UserType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->remove('username')
            ->remove('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'app.ui.redactor' => 'ROLE_REDACTOR',
                    'app.ui.translator' => 'ROLE_TRANSLATOR',
                    'app.ui.reviewer' => 'ROLE_REVIEWER',
                    'app.ui.publisher' => 'ROLE_PUBLISHER',
                    'app.ui.administrator' => 'ROLE_ADMIN',
                ],
                'by_reference' => false,
                'expanded' => true,
                'multiple' => true,
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, array($this, 'onPostSubmit'));
    }

    /**
     * @param FormEvent $event
     */
    public function onPostSubmit(FormEvent $event)
    {
        /** @var UserInterface $user */
        $user = $event->getData();

        $user->addRole(UserInterface::DEFAULT_ROLE);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sylius_app_user';
    }
}
