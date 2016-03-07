<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 29/02/2016
 * Time: 13:44
 */

namespace AppBundle\Controller;

use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\TaxonomyBundle\Doctrine\ORM\TaxonomyRepository;
use Sylius\Bundle\TaxonomyBundle\Doctrine\ORM\TaxonRepository;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Resource\ResourceActions;
use Sylius\Component\Taxonomy\Model\TaxonomyInterface;
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

        /** @var TaxonomyInterface $taxonomy */
        $taxonomy = $this->getTaxonomyRepository()->findOneBy(array('name' => 'forum'));

        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        $view = View::create($resources);

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate($configuration->getTemplate(ResourceActions::INDEX))
                ->setTemplateVar($this->metadata->getPluralName())
                ->setData([
                    'metadata' => $this->metadata,
                    'resources' => $resources,
                    'taxons' => $taxonomy->getTaxons(),
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

        /** @var TaxonomyInterface $taxonomy */
        $taxonomy = $this->getTaxonomyRepository()->findOneBy(array('name' => 'forum'));
        /** @var TaxonInterface $taxon */
        $taxon = $this->getTaxonRepository()->findOneBy(array('slug' => $permalink));

        if (!isset($taxon)) {
            throw new NotFoundHttpException('Requested taxon does not exist.');
        }

        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        $view = View::create($resources);

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate($configuration->getTemplate(ResourceActions::INDEX))
                ->setTemplateVar($this->metadata->getPluralName())
                ->setData([
                    'metadata' => $this->metadata,
                    'resources' => $resources,
                    'taxon' => $taxon,
                    'taxons' => $taxonomy->getTaxons(),
                    $this->metadata->getPluralName() => $resources,
                ])
            ;
        }

        return $this->viewHandler->handle($configuration, $view);
    }

    /**
     * @return TaxonomyRepository
     */
    protected function getTaxonomyRepository()
    {
        return $this->get('sylius.repository.taxonomy');
    }

    /**
     * @return TaxonRepository
     */
    protected function getTaxonRepository()
    {
        return $this->get('sylius.repository.taxon');
    }
}