<?php
/**
 * Created by PhpStorm.
 * User: loicfremont
 * Date: 23/08/2018
 * Time: 13:53
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class DurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return IntegerType::class;
    }
}