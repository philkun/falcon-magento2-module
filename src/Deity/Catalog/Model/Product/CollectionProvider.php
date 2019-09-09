<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Product;

use Deity\CatalogApi\Model\Product\CollectionProviderInterface;
use Magento\Catalog\Model\Config;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\Collection;

/**
 * Class CollectionProvider
 *
 * @package Deity\CatalogSearch\Model\Product
 */
class CollectionProvider implements CollectionProviderInterface
{

    /**
     * @var Config
     */
    private $catalogConfig;

    /**
     * @var Visibility
     */
    private $productVisibility;

    /**
     * CollectionProvider constructor.
     * @param Config $catalogConfig
     * @param Visibility $productVisibility
     */
    public function __construct(Config $catalogConfig, Visibility $productVisibility)
    {
        $this->catalogConfig = $catalogConfig;
        $this->productVisibility = $productVisibility;
    }

    /**
     * Gets the product collection from given layer, add all required data
     *
     * @param Layer $layer
     * @return Collection
     */
    public function getProductCollectionFromLayer(Layer $layer): Collection
    {
        $collection = $layer->getProductCollection();

        $collection
            ->addAttributeToSelect($this->catalogConfig->getProductAttributes())
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addUrlRewrite()
            ->setVisibility($this->productVisibility->getVisibleInSearchIds());

        return $collection;
    }
}
