<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 09/02/2016
 * Time: 12:46
 */

namespace AppBundle\Controller;

use JDJ\JeuBundle\Repository\JeuRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class GameController extends ResourceController
{

    /**
     * @param Request $request
     * @param $permalink
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexByMechanismAction(Request $request, $permalink)
    {
        $mechanism = $this->get('app.repository.mechanism')->findOneBy(array('slug' => $permalink));
        if (!isset($mechanism)) {
            throw new NotFoundHttpException('Requested mechanism does not exist.');
        }

        $paginator = $this
            ->getRepository()
            ->createPaginator(array('mechanism' => $mechanism), $request->get('sorting', $this->config->getSorting()))
            ->setMaxPerPage($this->config->getPaginationMaxPerPage())
            ->setCurrentPage($request->get('page', 1));

        $view = $this
            ->view()
            ->setTemplate($this->config->getTemplate('index_by_mechanism.html'))
            ->setData(array(
                'mechanism'    => $mechanism,
                'games' => $paginator,
            ));

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     * @param $permalink
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexByThemeAction(Request $request, $permalink)
    {
        $theme = $this->get('app.repository.theme')->findOneBy(array('slug' => $permalink));
        if (!isset($theme)) {
            throw new NotFoundHttpException('Requested theme does not exist.');
        }

        $paginator = $this
            ->getRepository()
            ->createPaginator(array('theme' => $theme), $request->get('sorting', $this->config->getSorting()))
            ->setMaxPerPage($this->config->getPaginationMaxPerPage())
            ->setCurrentPage($request->get('page', 1));

        $view = $this
            ->view()
            ->setTemplate($this->config->getTemplate('index_by_mechanism.html'))
            ->setData(array(
                'theme'    => $theme,
                'games' => $paginator,
            ));

        return $this->handleView($view);
    }


    /**
     * @return JeuRepository
     */
    public function getRepository()
    {
        return $this->get('app.repository.game');
    }
}