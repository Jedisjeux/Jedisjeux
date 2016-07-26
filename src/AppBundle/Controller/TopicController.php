<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 29/02/2016
 * Time: 13:44
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Taxon;
use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use AppBundle\Repository\TaxonRepository;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicController extends ResourceController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function indexWithTaxonsAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::INDEX);

        /** @var TaxonInterface $rootTaxon */
        $rootTaxon = $this->getTaxonRepository()->findOneBy(array('code' => Taxon::CODE_FORUM));

        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        $view = View::create($resources);

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate($configuration->getTemplate(ResourceActions::INDEX))
                ->setTemplateVar($this->metadata->getPluralName())
                ->setData([
                    'metadata' => $this->metadata,
                    'resources' => $resources,
                    'taxons' => $rootTaxon->getChildren(),
                    $this->metadata->getPluralName() => $resources,
                ])
            ;
        }

        return $this->viewHandler->handle($configuration, $view);
    }

    /**
     * @param Request $request
     * @param $permalink
     * @return Response
     */
    public function indexByTaxonSlugAction(Request $request, $permalink)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::INDEX);

        /** @var TaxonInterface $rootTaxon */
        $rootTaxon = $this->getTaxonRepository()->findOneBy(array('code' => Taxon::CODE_FORUM));
        /** @var TaxonInterface $taxon */
        $taxon = $this->getTaxonRepository()->findOneBySlug($permalink);

        if (!isset($taxon)) {
            throw new NotFoundHttpException('Requested taxon does not exist.');
        }

        $resources = $this
            ->repository
            ->createPaginator(array('mainTaxon' => $taxon), $request->get('sorting', $configuration->getSorting()))
            ->setMaxPerPage($configuration->getPaginationMaxPerPage())
            ->setCurrentPage($request->get('page', 1));

        $view = View::create($resources);

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate($configuration->getTemplate(ResourceActions::INDEX))
                ->setTemplateVar($this->metadata->getPluralName())
                ->setData([
                    'metadata' => $this->metadata,
                    'resources' => $resources,
                    'taxon' => $taxon,
                    'taxons' => $rootTaxon->getChildren(),
                    $this->metadata->getPluralName() => $resources,
                ])
            ;
        }

        return $this->viewHandler->handle($configuration, $view);
    }


    /**
     * @return TaxonRepository
     */
    protected function getTaxonRepository()
    {
        return $this->get('sylius.repository.taxon');
    }
}