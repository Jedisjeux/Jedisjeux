<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 25/02/2016
 * Time: 13:59
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Taxon;
use AppBundle\Entity\Topic;
use AppBundle\Repository\TaxonRepository;
use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\ResourceActions;
use Sylius\Component\Taxonomy\Model\TaxonTranslationInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PostController extends ResourceController
{
    public function indexByTopicAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::INDEX);

        /** @var TaxonTranslationInterface $rootTaxon */
        $rootTaxon = $this->getTaxonRepository()->findOneBy(array('code' => Taxon::CODE_FORUM));

        $criteria = $configuration->getCriteria();
        /** @var Topic $topic */
        $topic = $this->get('app.repository.topic')->find($criteria['topic']);

        $onlyPublic = $this->getAuthorizationChecker()->isGranted('ROLE_STAFF') ? false : true;

        if (null !== $topic->getMainTaxon() and !$topic->getMainTaxon()->isPublic() and $onlyPublic) {
            throw new AccessDeniedException();
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
                    'posts' => $resources,
                    'topic' => $topic,
                    'taxons' => $rootTaxon->getChildren(),
                    $this->metadata->getPluralName() => $resources,
                ])
            ;
        }

        return $this->viewHandler->handle($configuration, $view);
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