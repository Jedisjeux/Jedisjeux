<?php

declare(strict_types=1);

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Filter\Widget;

use AppBundle\Filter\ViewData\PagerAwareViewData;
use ONGR\ElasticsearchBundle\Result\DocumentIterator;
use ONGR\ElasticsearchDSL\Search;
use ONGR\FilterManagerBundle\Filter\FilterState;
use ONGR\FilterManagerBundle\Filter\Helper\ViewDataFactoryInterface;
use ONGR\FilterManagerBundle\Filter\ViewData;
use ONGR\FilterManagerBundle\Filter\Widget\AbstractFilter;
use ONGR\FilterManagerBundle\Search\SearchRequest;
use Symfony\Component\HttpFoundation\Request;

final class Pager extends AbstractFilter implements ViewDataFactoryInterface
{
    /**
     * @param FilterState|null $state
     *
     * @return int
     */
    public function getCountPerPage(?FilterState $state): int
    {
        if (null !== $state) {
            return (int) $state->getOption('limit', 10);
        }

        return (int) $this->getOption('limit', 10);
    }

    /**
     * {@inheritdoc}
     */
    public function getState(Request $request): FilterState
    {
        $state = parent::getState($request);
        // Reset pager with any filter.
        $state->setUrlParameters([]);
        $page = (int) $state->getValue();
        $state->setValue($page < 1 ? 1 : $page);
        $state->addOption('limit', (int) $request->get('limit', (int) $this->getOption('limit', 10)));

        return $state;
    }

    /**
     * {@inheritdoc}
     */
    public function modifySearch(Search $search, ?FilterState $state = null, ?SearchRequest $request = null): void
    {
        if ($state && $state->isActive()) {
            $search->setFrom($this->getCountPerPage($state) * ($state->getValue() - 1));
        }

        $search->setSize($this->getCountPerPage($state));
    }

    /**
     * {@inheritdoc}
     */
    public function preProcessSearch(Search $search, Search $relatedSearch, ?FilterState $state = null)
    {
        // Nothing to do here.
    }

    /**
     * {@inheritdoc}
     */
    public function createViewData(): PagerAwareViewData
    {
        return new PagerAwareViewData();
    }

    /**
     * {@inheritdoc}
     */
    public function getViewData(DocumentIterator $result, ViewData $data): ViewData
    {
        /** @var ViewData\PagerAwareViewData $data */
        $data->setData(
            $result->count(),
            $data->getState()->getValue(),
            $data->getState()->getOption('limit'),
            $this->getOption('max_pages', 10)
        );

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function isRelated(): bool
    {
        return false;
    }
}
