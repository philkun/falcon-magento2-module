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
use Magento\Catalog\Model\Layer\Filter\DataProvider\Price as PriceCurrency;
use Magento\Catalog\Model\Layer\Filter\DataProvider\PriceFactory;
use Magento\Catalog\Model\Layer\Filter\Item;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Framework\Pricing\PriceCurrencyInterface;

/**
 * Class Price
 *
 * @package Deity\Catalog\Model\Product\Filter
 */
class Price implements FilterDataRendererInterface
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
     * @var PriceCurrency
     */
    private $dataProvider;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var PriceFactory
     */
    private $priceFactory;

    /**
     * Price constructor.
     *
     * @param FilterInterfaceFactory $filterFactory
     * @param FilterOptionInterfaceFactory $filterOptionFactory
     * @param PriceFactory $dataProviderFactory
     * @param PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        FilterInterfaceFactory $filterFactory,
        FilterOptionInterfaceFactory $filterOptionFactory,
        PriceFactory $dataProviderFactory,
        PriceCurrencyInterface $priceCurrency
    ) {
        $this->filterFactory = $filterFactory;
        $this->filterOptionFactory = $filterOptionFactory;
        $this->priceFactory = $dataProviderFactory;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * Get filter data for price attribute
     *
     * @param Layer $layer
     * @param AbstractFilter $magentoFilter
     * @param array $selectedValues
     * @return FilterInterface
     * @throws LocalizedException
     */
    public function getFilterData(Layer $layer, AbstractFilter $magentoFilter, array $selectedValues = [])
    : FilterInterface
    {
        $this->dataProvider = $this->priceFactory->create(['layer' => $layer]);
        /** @var FilterInterface $filterObject */
        $filterObject = $this->filterFactory->create(
            [
                FilterInterface::LABEL => (string)$magentoFilter->getName(),
                FilterInterface::CODE => $magentoFilter->getAttributeModel()->getAttributeCode(),
                FilterInterface::TYPE => $magentoFilter->getAttributeModel()->getBackendType(),
                FilterInterface::ATTRIBUTE_ID => (int)$magentoFilter->getAttributeModel()->getAttributeId()
            ]
        );
        $magentoOptions = $magentoFilter->getItems();

        if (!empty($selectedValues)) {
            //price filter options can return empty
            /** @var FilterOptionInterface $filterOption */
            $filterOption =$this->filterOptionFactory->create(
                [
                    FilterOptionInterface::LABEL => $this->renderRangeLabel($selectedValues[0]),
                    FilterOptionInterface::VALUE => $selectedValues[0],
                    FilterOptionInterface::COUNT => $layer->getProductCollection()->getSize(),
                    FilterOptionInterface::IS_SELECTED => 1
                ]
            );
            $filterObject->addOption($filterOption);
            return $filterObject;
        }
        /** @var Item $magentoOption */
        foreach ($magentoOptions as $magentoOption) {
            /** @var FilterOptionInterface $filterOption */
            $filterOption =$this->filterOptionFactory->create(
                [
                    FilterOptionInterface::LABEL => (string)$magentoOption->getData('label'),
                    FilterOptionInterface::VALUE => $magentoOption->getValueString(),
                    FilterOptionInterface::COUNT => (int)$magentoOption->getData('count'),
                    FilterOptionInterface::IS_SELECTED => in_array(
                        (string)$magentoOption->getValueString(),
                        $selectedValues
                    )
                ]
            );
            $filterObject->addOption($filterOption);
        }

        return $filterObject;
    }

    /**
     * Prepare text of range label
     *
     * @param string $priceRange
     * @return float|Phrase
     */
    private function renderRangeLabel($priceRange)
    {
        if (strpos($priceRange, '-') === false) {
            return $this->priceCurrency->format($priceRange);
        } else {
            list($fromPrice, $toPrice) = explode('-', $priceRange);
        }

        if ($fromPrice === '') {
            $formattedFromPrice = $this->priceCurrency->format($toPrice);
            return __('Less than %1', $formattedFromPrice);
        }
        $formattedFromPrice = $this->priceCurrency->format($fromPrice);
        if ($toPrice === '') {
            return __('%1 and above', $formattedFromPrice);
        } elseif ($fromPrice == $toPrice && $this->dataProvider->getOnePriceIntervalValue()
        ) {
            return $formattedFromPrice;
        } else {
            if ($fromPrice != $toPrice) {
                $toPrice -= .01;
            }

            return __('%1 - %2', $formattedFromPrice, $this->priceCurrency->format($toPrice));
        }
    }
}
