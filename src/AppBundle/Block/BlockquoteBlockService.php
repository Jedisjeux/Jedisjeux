<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 03/02/2016
 * Time: 10:10
 */

namespace AppBundle\Block;

use Sonata\BlockBundle\Block\BaseBlockService;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class BlockquoteBlockService extends BaseBlockService
{
    public function getName()
    {
        return 'Blockquote';
    }

    /**
     * Define valid options for a block of this type.
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'url'      => false,
            'title'    => 'Feed items',
            'template' => 'frontend/content/block/block_blockquote.html.twig',
        ));
    }
}