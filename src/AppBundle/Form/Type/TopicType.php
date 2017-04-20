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
use Doctrine\ORM\EntityRepository;
use Sylius\Bundle\TaxonomyBundle\Form\Type\TaxonAutocompleteChoiceType;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
