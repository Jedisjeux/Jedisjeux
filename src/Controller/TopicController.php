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

use App\Entity\Taxon;
use App\Repository\TopicRepository;
use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use App\Repository\TaxonRepository;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
     * @param string $slug
     * @return Response
     */
    public function indexByTaxonAction(Request $request, $slug)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::INDEX);

        /** @var TaxonInterface $rootTaxon */
        $rootTaxon = $this->getTaxonRepository()->findOneBy(array('code' => Taxon::CODE_FORUM));
        /** @var Taxon $taxon */
        $taxon = $this->getTaxonRepository()->findOneBySlug($slug, $this->getParameter('locale'));

        if (null === $taxon) {
            throw new NotFoundHttpException('Requested taxon does not exist.'.$slug);
        }

        $this->taxonIsGrantedOr403($taxon);

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
                    'taxons' => $rootTaxon->getChildren(),
                    $this->metadata->getPluralName() => $resources,
                ])
            ;
        }

        return $this->viewHandler->handle($configuration, $view);
    }

    /**
     * @param Taxon $taxon
     *
     * @throws AccessDeniedException
     */
    protected function taxonIsGrantedOr403(Taxon $taxon)
    {
        $onlyPublic = $this->getAuthorizationChecker()->isGranted('ROLE_STAFF') ? false : true;

        if (!$taxon->isPublic() and $onlyPublic) {
            throw new AccessDeniedException();
        }
    }

    /**
     * @return AuthorizationChecker
     */
    protected function getAuthorizationChecker()
    {
        return $this->get('security.authorization_checker');
    }

    /**
     * @return TaxonRepository
     */
    protected function getTaxonRepository()
    {
        return $this->get('sylius.repository.taxon');
    }
}
