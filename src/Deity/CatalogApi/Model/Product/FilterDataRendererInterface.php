<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Model\Product;

use Deity\CatalogApi\Api\Data\FilterInterface;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Filter\AbstractFilter;

/**
 * Interface FilterDataRendererInterface
 *
 * @package Deity\CatalogApi\Model\Product
 */
interface FilterDataRendererInterface
{
    /**
     * Get structured data for given magento filter
     *
     * @param Layer $layer
     * @param AbstractFilter $magentoFilter
     * @param array $selectedValues
     * @return FilterInterface
     */
    public function getFilterData(Layer $layer, AbstractFilter $magentoFilter, array $selectedValues = [])
    : FilterInterface;
}
