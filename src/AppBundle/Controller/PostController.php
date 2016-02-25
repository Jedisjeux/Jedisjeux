<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 25/02/2016
 * Time: 13:59
 */

namespace AppBundle\Controller;

use Hateoas\Configuration\Route;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PostController extends ResourceController
{
    public function indexByTopicAction(Request $request)
    {
        $this->isGrantedOr403('index');

        $criteria = $this->config->getCriteria();
        $sorting = $this->config->getSorting();

        $repository = $this->getRepository();

        $topic = $this->get('app.repository.topic')->find($criteria['topic']);

        if ($this->config->isPaginated()) {
            $resources = $this->resourceResolver->getResource(
                $repository,
                'createPaginator',
                array($criteria, $sorting)
            );
            $resources->setCurrentPage($request->get('page', 1), true, true);
            $resources->setMaxPerPage($this->config->getPaginationMaxPerPage());

            if ($this->config->isApiRequest()) {
                $resources = $this->getPagerfantaFactory()->createRepresentation(
                    $resources,
                    new Route(
                        $request->attributes->get('_route'),
                        array_merge($request->attributes->get('_route_params'), $request->query->all())
                    )
                );
            }
        } else {
            $resources = $this->resourceResolver->getResource(
                $repository,
                'findBy',
                array($criteria, $sorting, $this->config->getLimit())
            );
        }

       $view = $this
            ->view()
            ->setTemplate($this->config->getTemplate('index.html'))
            ->setTemplateVar($this->config->getPluralResourceName())
            ->setData(array(
                'posts' => $resources,
                'topic' => $topic,
            ))
        ;

        return $this->handleView($view);
    }
}