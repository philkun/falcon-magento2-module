<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Model;

use Magento\Framework\Api\AttributeValue;

/**
 * Interface FilterProductCustomAttributeInterface
 *
 * @package Deity\CatalogApi\Model
 */
interface FilterProductCustomAttributeInterface
{
    /**
     * Delete custom attribute
     *
     * @param AttributeValue[] $attributes set objects attributes @example ['attribute_code'=>'attribute_object']
     * @return AttributeValue[]
     */
    public function execute(array $attributes): array;
}
