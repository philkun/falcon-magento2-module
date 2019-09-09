<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Model\Product;

use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\ResourceModel\Product\Collection;

/**
 * Class CollectionProvider
 *
 * @package Deity\CatalogSearch\Model\Product
 */
interface CollectionProviderInterface
{
    /**
     * Gets the product collection from given layer, add all required data
     *
     * @param Layer $layer
     * @return Collection
     */
    public function getProductCollectionFromLayer(Layer $layer): Collection;
}
