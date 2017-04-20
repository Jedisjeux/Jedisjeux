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
use Sylius\Bundle\TaxonomyBundle\Form\Type\TaxonAutocompleteChoiceType;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicType extends AbstractType
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
            ->add('mainPost', PostType::class,  array(
                'label' => false,
            ))
            ->add('mainTaxon', TaxonAutocompleteChoiceType::class, array(
                'label' => 'label.category',
                //'choice_label' => 'name',
                //'root' => Taxon::CODE_FORUM,
//                'filter' => function(Taxon $taxon) use ($onlyPublic) {
//                    if ($onlyPublic) {
//                        if (!$taxon->isPublic()) {
//                            return false;
//                        }
//                    }
//
//                    return $taxon;
//                },
                'multiple' => false,
                'placeholder' => 'Choisissez une catégorie',
                'required' => false,
            ));
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix()
    {
        return 'app_topic';
    }
}
