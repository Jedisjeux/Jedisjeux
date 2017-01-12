<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 03/02/2016
 * Time: 10:16
 */

namespace AppBundle\Block;
use Sonata\BlockBundle\Block\BaseBlockService;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class SingleImageBlockService extends BaseBlockService
{
    public function getName()
    {
        return 'SingleImage';
    }

    /**
     * Define valid options for a block of this type.
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'url'      => false,
            'title'    => 'Feed items',
            'template' => 'frontend/content/block/block_single_image.html.twig',
        ));
    }
}