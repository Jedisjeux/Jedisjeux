<?php

/*
 * This file is part of jedisjeux project.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class StatisticsController extends AbstractController
{
    public function indexAction(): Response
    {
        // Statistics are rendered by the Dashboard:Statistics live component
        return $this->render('backend/index.html.twig');
    }
}
