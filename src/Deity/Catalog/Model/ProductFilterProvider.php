<?php
declare(strict_types=1);

namespace Deity\Catalog\Model;

use Deity\CatalogApi\Api\ProductFilterProviderInterface;
use Deity\CatalogApi\Model\Product\FilterDataRendererInterface;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Filter\AbstractFilter;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class ProductFilterProvider
 *
 * @package Deity\Catalog\Model
 */
class ProductFilterProvider implements ProductFilterProviderInterface
{
    const CATEGORY_FILTER   = 'category';
    const ATTRIBUTE_FILTER  = 'attribute';
    const PRICE_FILTER      = 'price';
    const DECIMAL_FILTER    = 'decimal';

    /**
     * @var Layer\FilterList
     */
    private $filterList;

    /**
     * @var string[][]
     */
    private $filterValues = [];

    /**
     * @var FilterDataRendererInterface[]
     */
    private $filterRenderers;

    /**
     * ProductFilterProvider constructor.
     * @param Layer\FilterList $filterList
     * @param string[] $filterRenderers
     * @throws InputException
     */
    public function __construct(
        Layer\FilterList $filterList,
        array $filterRenderers
    ) {
        $this->filterList = $filterList;
        foreach ($filterRenderers as $filterRenderer) {
            if (!$filterRenderer instanceof FilterDataRendererInterface) {
                throw new InputException(__('Filter data renderer must implement FilterDataRendererInterface'));
            }
        }
        $this->filterRenderers = $filterRenderers;
    }

    /**
     * @inheritdoc
     */
    public function getFilterList(Layer $layer, ?SearchCriteriaInterface $searchCriteria): array
    {

        $this->presetFilterValues($searchCriteria);
        
        /** @var AbstractFilter[] $magentoFilters */
        $magentoFilters = $this->filterList->getFilters($layer);
        $resultFilters = [];
        foreach ($magentoFilters as $magentoFilter) {
            if (!$magentoFilter->getItemsCount() && !$this->isFilterSelected($magentoFilter)) {
                continue;
            }

            $filterDataRenderer = $this->getFilterDataRenderer($magentoFilter);

            $resultFilters[] = $filterDataRenderer->getFilterData(
                $layer,
                $magentoFilter,
                $this->getSelectedValuesForFilter($magentoFilter)
            );
        }
        return $resultFilters;
    }

    /**
     * Get selected values per filter object
     *
     * @param AbstractFilter $magentoFilter
     * @return array
     */
    private function getSelectedValuesForFilter(AbstractFilter $magentoFilter): array
    {
        if (!$this->isFilterSelected($magentoFilter)) {
            return [];
        }

        return $this->filterValues[$magentoFilter->getRequestVar()];
    }

    /**
     * Check if filter values are selected
     *
     * @param AbstractFilter $magentoFilter
     * @return bool
     */
    private function isFilterSelected(AbstractFilter $magentoFilter): bool
    {
        return isset($this->filterValues[$magentoFilter->getRequestVar()]);
    }

    /**
     * Parse Filter Selected values
     *
     * @param SearchCriteriaInterface|null $searchCriteria
     * @return $this
     */
    private function presetFilterValues(?SearchCriteriaInterface $searchCriteria)
    {
        if ($searchCriteria === null) {
            return $this;
        }

        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $this->filterValues[$filter->getField()][] = $filter->getValue();
            }
        }

        return $this;
    }

    /**
     * Get filter data
     *
     * @param AbstractFilter $magentoFilter
     * @return FilterDataRendererInterface
     * @throws LocalizedException
     */
    private function getFilterDataRenderer(AbstractFilter $magentoFilter): FilterDataRendererInterface
    {
        if ($magentoFilter->getRequestVar() === 'cat') {
            return $this->filterRenderers[self::CATEGORY_FILTER];
        }

        if ($magentoFilter->getAttributeModel()->getAttributeCode() == 'price') {
            return $this->filterRenderers[self::PRICE_FILTER];
        }

        return $this->filterRenderers[self::ATTRIBUTE_FILTER];
    }
}
