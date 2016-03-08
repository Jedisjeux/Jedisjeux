<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 09/02/2016
 * Time: 12:46
 */

namespace AppBundle\Controller;

use FOS\RestBundle\View\View;
use JDJ\JeuBundle\Repository\JeuRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\ResourceActions;
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
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::INDEX);

        $mechanism = $this->get('app.repository.mechanism')->findOneBy(array('slug' => $permalink));
        if (!isset($mechanism)) {
            throw new NotFoundHttpException('Requested mechanism does not exist.');
        }

        $resources = $this
            ->getRepository()
            ->createPaginator(array('mechanism' => $mechanism), $request->get('sorting', $configuration->getSorting()))
            ->setMaxPerPage($configuration->getPaginationMaxPerPage())
            ->setCurrentPage($request->get('page', 1));

        $view = View::create($resources);

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate($configuration->getTemplate('index_by_mechanism.html'))
                ->setTemplateVar($this->metadata->getPluralName())
                ->setData([
                    'metadata' => $this->metadata,
                    'mechanism' => $mechanism,
                    'resources' => $resources,
                    $this->metadata->getPluralName() => $resources,
                ]);
        }

        return $this->viewHandler->handle($configuration, $view);
    }

    /**
     * @param Request $request
     * @param $permalink
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexByThemeAction(Request $request, $permalink)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::INDEX);

        $theme = $this->get('app.repository.theme')->findOneBy(array('slug' => $permalink));
        if (!isset($theme)) {
            throw new NotFoundHttpException('Requested theme does not exist.');
        }

        $resources = $this
            ->getRepository()
            ->createPaginator(array('theme' => $theme), $request->get('sorting', $configuration->getSorting()))
            ->setMaxPerPage($configuration->getPaginationMaxPerPage())
            ->setCurrentPage($request->get('page', 1));

        $view = View::create($resources);

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate($configuration->getTemplate('index_by_theme.html'))
                ->setTemplateVar($this->metadata->getPluralName())
                ->setData(array(
                    'metadata' => $this->metadata,
                    'theme' => $theme,
                    'games' => $resources,
                ));
        }

        return $this->viewHandler->handle($configuration, $view);
    }


    /**
     * @return JeuRepository
     */
    public function getRepository()
    {
        return $this->get('app.repository.game');
    }
}