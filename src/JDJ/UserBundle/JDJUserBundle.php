<?php

namespace JDJ\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class JDJUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
