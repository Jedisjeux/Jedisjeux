<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Type;

use App\Entity\Taxon;
use Doctrine\ORM\EntityRepository;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicType extends AbstractType
{
    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * @param CustomerContextInterface $authorizationChecker
     */
    public function setAuthorizationChecker(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $onlyPublic = $this->authorizationChecker->isGranted('ROLE_STAFF') ? false : true;

        $builder
            ->add('title', null, [
                'label' => 'sylius.ui.title',
            ])
            ->add('mainPost', PostType::class, [
                'label' => false,
            ])
            ->add('mainTaxon', EntityType::class, [
                'label' => 'sylius.ui.category',
                'class' => 'App:Taxon',
                'group_by' => 'parent',
                'query_builder' => function (EntityRepository $er) use ($onlyPublic) {
                    $queryBuilder = $er->createQueryBuilder('o')
                        ->join('o.root', 'rootTaxon')
                        ->where('rootTaxon.code = :code')
                        ->andWhere('o.parent IS NOT NULL')
                        ->setParameter('code', Taxon::CODE_FORUM)
                        ->orderBy('o.position');

                    if ($onlyPublic) {
                        $queryBuilder
                            ->andWhere('o.public = :public')
                            ->setParameter('public', 1);
                    }

                    return $queryBuilder;
                },
                'expanded' => false,
                'multiple' => false,
                'placeholder' => 'Choisissez une catégorie',
                'required' => false,
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'topic',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_topic';
    }
}
