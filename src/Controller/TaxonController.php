<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Repository\TaxonRepository;
use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\ResourceActions;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TaxonController extends ResourceController
{
    /**
     * @return Response
     */
    public function indexByCodeAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::INDEX);
        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        $view = View::create($resources);

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate($configuration->getTemplate(ResourceActions::INDEX.'.html'))
                ->setTemplateVar($this->metadata->getPluralName())
                ->setData([
                    'configuration' => $configuration,
                    'metadata' => $this->metadata,
                    'resources' => $resources,
                    $this->metadata->getPluralName() => $resources,
                    'current' => $this->getCurrent($request),
                ])
            ;
        }

        return $this->viewHandler->handle($configuration, $view);
    }

    /**
     * @return null|TaxonInterface
     */
    protected function getCurrent(Request $request)
    {
        if (null === $currentSlug = $request->get('currentSlug')) {
            return null;
        }

        $current = $this->getRepository()->findOneBySlug($currentSlug, $this->getParameter('locale'));

        if (!isset($current)) {
            throw new NotFoundHttpException('Requested current taxon does not exist.');
        }

        return $current;
    }

    /**
     * @return TaxonRepository|RepositoryInterface
     */
    protected function getRepository()
    {
        return $this->repository;
    }
}
