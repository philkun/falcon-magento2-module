<?php
declare(strict_types=1);

namespace Deity\FalconCacheApi\Model;

/**
 * Interface FalconApiAdapterInterface
 *
 * @package Deity\FalconCacheApi\Model
 */
interface FalconApiAdapterInterface
{

    /**
     * Flush the cache storage of falcon
     *
     * @param string $entityTypeCode
     * @return bool
     */
    public function flushCacheForGivenType(string $entityTypeCode): bool;

    /**
     * Flush cache for given entities data
     *
     * @param array $entities
     * @return bool
     */
    public function flushCacheForEntities(array $entities): bool;

    /**
     * Get error message
     *
     * @return string
     */
    public function getError(): string;
}
