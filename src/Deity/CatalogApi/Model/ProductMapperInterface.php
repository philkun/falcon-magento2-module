<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface ProductMapperInterface
 *
 * @package Deity\CatalogApi\Model
 */
interface ProductMapperInterface
{
    /**
     * Perform mapping of magento product to falcon product
     *
     * @param ProductInterface $product
     * @param ExtensibleDataInterface $falconProduct
     */
    public function map(ProductInterface $product, ExtensibleDataInterface $falconProduct): void;
}
