<?php
declare(strict_types=1);

namespace Deity\FalconCacheApi\Model;

/**
 * Interface CacheTagMapperInterface
 *
 * @package Deity\FalconCacheApi\Model
 */
interface CacheTagMapperInterface
{
    const PRODUCT_CACHE_TAG = 'Product';

    const CATEGORY_CACHE_TAG = 'Category';

    /**
     * Get cache tags supported by Falcon
     *
     * @return array
     */
    public function getAvailableCacheTypes(): array;

    /**
     * Map magento cache tags to falcon cache tags
     *
     * @param array $tags
     * @return array
     */
    public function mapMagentoCacheTagsToFalconApiCache(array $tags): array;

    /**
     * Filter out magento tags
     *
     * @param array $tags
     * @return array
     */
    public function filterMagentoCacheTags(array $tags): array;
}
