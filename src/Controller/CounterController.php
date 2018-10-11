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

use App\Repository\ArticleRepository;
use App\Repository\PersonRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductReviewRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/counter")
 */
class CounterController extends Controller
{
    /**
     * @Route("/", name="app_counter_index")
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('frontend/homepage/index/_counters.html.twig', array(
            'articleCount' => $this->getArticleRepository()->findNbResults(),
            'productCount' => $this->getProductRepository()->findNbResults(),
            'personCount' => $this->getPersonRepository()->findNbResults(),
            'userCount' => $this->getUserRepository()->findNbResults(),
            'ratingCount' => $this->getProductRepository()->findNbResults(),
        ));
    }

    /**
     * @return ArticleRepository
     */
    protected function getArticleRepository()
    {
        return $this->get('app.repository.article');
    }

    /**
     * @return ProductRepository
     */
    protected function getProductRepository()
    {
        return $this->get('sylius.repository.product');
    }

    /**
     * @return PersonRepository
     */
    protected function getPersonRepository()
    {
        return $this->get('app.repository.person');
    }

    /**
     * @return UserRepository
     */
    protected function getUserRepository()
    {
        return $this->get('sylius.repository.app_user');
    }

    /**
     * @return ProductReviewRepository
     */
    protected function getProductReviewRepository()
    {
        return $this->get('sylius.repository.product_review');
    }
}
