<?php
declare(strict_types=1);

namespace Deity\FalconCacheApi\Model;

/**
 * Interface CacheManagementInterface
 *
 * @package Deity\FalconCacheApi\Model
 */
interface CacheManagementInterface
{
    /**
     * Remove all magento entries in Falcon Cache
     *
     * @return bool
     */
    public function cleanFalconCache(): bool;

    /**
     * Clean Falcon Cache for given tags
     *
     * @param array $tags
     * @return bool
     */
    public function cleanFalconCacheForTags(array $tags): bool;
}
