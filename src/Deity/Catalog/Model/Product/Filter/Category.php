<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Product\Filter;

use Deity\CatalogApi\Api\Data\FilterInterface;
use Deity\CatalogApi\Api\Data\FilterInterfaceFactory;
use Deity\CatalogApi\Api\Data\FilterOptionInterface;
use Deity\CatalogApi\Api\Data\FilterOptionInterfaceFactory;
use Deity\CatalogApi\Model\Product\FilterDataRendererInterface;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Filter\AbstractFilter;
use Magento\Catalog\Model\Layer\Filter\Item;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Category
 *
 * @package Deity\Catalog\Model\Product\Filter
 */
class Category implements FilterDataRendererInterface
{
    /**
     * @var FilterInterfaceFactory
     */
    private $filterFactory;

    /**
     * @var FilterOptionInterfaceFactory
     */
    private $filterOptionFactory;

    /**
     * Attribute constructor.
     * @param FilterInterfaceFactory $filterFactory
     * @param FilterOptionInterfaceFactory $filterOptionFactory
     */
    public function __construct(
        FilterInterfaceFactory $filterFactory,
        FilterOptionInterfaceFactory $filterOptionFactory
    ) {
        $this->filterFactory = $filterFactory;
        $this->filterOptionFactory = $filterOptionFactory;
    }

    /**
     * Get filter data for category
     *
     * @param Layer $layer
     * @param AbstractFilter $magentoFilter
     * @param array $selectedValues
     * @return FilterInterface
     * * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @throws LocalizedException
     */
    public function getFilterData(
        Layer $layer,
        AbstractFilter $magentoFilter,
        array $selectedValues = []
    ) : FilterInterface {
        /** @var FilterInterface $filterObject */
        $filterObject = $this->filterFactory->create(
            [
                FilterInterface::LABEL => (string)$magentoFilter->getName(),
                FilterInterface::CODE => $magentoFilter->getRequestVar(),
                FilterInterface::TYPE => 'int',
                FilterInterface::ATTRIBUTE_ID => 0
            ]
        );
        $magentoOptions = $magentoFilter->getItems();

        /** @var Item $magentoOption */
        foreach ($magentoOptions as $magentoOption) {
            /** @var FilterOptionInterface $filterOption */
            $filterOption =$this->filterOptionFactory->create(
                [
                    FilterOptionInterface::LABEL => (string)$magentoOption->getData('label'),
                    FilterOptionInterface::VALUE => $magentoOption->getValueString(),
                    FilterOptionInterface::COUNT => (int)$magentoOption->getData('count'),
                    FilterOptionInterface::IS_SELECTED => false
                ]
            );
            $filterObject->addOption($filterOption);
        }

        if (!empty($selectedValues)) {
            $currentCategory = $layer->getCurrentCategory();
            $filterOption =$this->filterOptionFactory->create(
                [
                    FilterOptionInterface::LABEL => $currentCategory->getName(),
                    FilterOptionInterface::VALUE => $currentCategory->getId(),
                    FilterOptionInterface::COUNT => $layer->getProductCollection()->getSize(),
                    FilterOptionInterface::IS_SELECTED => true
                ]
            );
            $filterObject->addOption($filterOption);
        }

        return $filterObject;
    }
}
