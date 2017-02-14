<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Taxon;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicType extends AbstractResourceType
{
    /**
     * @var AuthorizationChecker
     */
    protected $authorizationChecker;

    /**
     * @param CustomerContextInterface $authorizationChecker
     */
    public function setAuthorizationChecker($authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $onlyPublic = $this->authorizationChecker->isGranted('ROLE_STAFF') ? false : true;


        $builder
            ->add('title', null, array(
                'label' => 'label.title',
            ))
            ->add('mainPost', 'app_post',  array(
                'label' => false,
            ))
            ->add('mainTaxon', 'sylius_taxon_choice', array(
                'label' => 'label.category',
                'choice_label' => 'name',
                'root' => Taxon::CODE_FORUM,
                'filter' => function(Taxon $taxon) use ($onlyPublic) {
                    if ($onlyPublic) {
                        if (!$taxon->isPublic()) {
                            return false;
                        }
                    }

                    return $taxon;
                },
                'multiple' => false,
                'placeholder' => 'Choisissez une catégorie',
                'required' => false,
            ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'cascade_validation' => true,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'app_topic';
    }
}
