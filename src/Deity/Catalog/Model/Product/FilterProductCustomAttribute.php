<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Product;

use Deity\CatalogApi\Model\FilterProductCustomAttributeInterface;

/**
 * Class FilterProductCustomAttribute
 *
 * @package Deity\Catalog\Model\Product
 */
class FilterProductCustomAttribute implements FilterProductCustomAttributeInterface
{
    /**
     * @var array
     */
    private $blackList;

    /**
     * @param array $blackList
     */
    public function __construct(array $blackList = [])
    {
        $this->blackList = $blackList;
    }

    /**
     * Delete custom attribute
     *
     * @param array $attributes set objects attributes @example ['attribute_code'=>'attribute_object']
     * @return array
     */
    public function execute(array $attributes): array
    {
        return array_diff_key($attributes, array_flip($this->blackList));
    }
}
