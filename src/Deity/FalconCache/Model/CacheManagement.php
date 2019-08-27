<?php
declare(strict_types=1);

namespace Deity\FalconCache\Model;

use Deity\FalconCacheApi\Model\CacheManagementInterface;
use Deity\FalconCacheApi\Model\CacheTagMapperInterface;
use Deity\FalconCacheApi\Model\FalconApiAdapterInterface;

/**
 * Class CacheManagement
 *
 * @package Deity\FalconCache\Model
 */
class CacheManagement implements CacheManagementInterface
{

    /**
     * @var FalconApiAdapterInterface
     */
    private $apiAdapter;

    /**
     * @var CacheTagMapperInterface
     */
    private $cacheTagMapper;

    /**
     * CacheManagement constructor.
     * @param FalconApiAdapterInterface $apiAdapter
     * @param CacheTagMapperInterface $cacheTagMapper
     */
    public function __construct(
        FalconApiAdapterInterface $apiAdapter,
        CacheTagMapperInterface $cacheTagMapper
    ) {
        $this->cacheTagMapper = $cacheTagMapper;
        $this->apiAdapter = $apiAdapter;
    }

    /**
     * Remove all magento entries in Falcon Cache
     *
     * @return bool
     */
    public function cleanFalconCache(): bool
    {
        $result = true;
        foreach ($this->getAvailableCacheTypes() as $entityType) {
            $result = $result && $this->apiAdapter->flushCacheForGivenType($entityType);
        }
        return $result;
    }

    /**
     * Clean Falcon Cache for given tags
     *
     * @param array $tags
     * @return bool
     */
    public function cleanFalconCacheForTags(array $tags): bool
    {
        $falconTags = $this->cacheTagMapper->mapMagentoCacheTagsToFalconApiCache(
            $this->cacheTagMapper->filterMagentoCacheTags($tags)
        );

        return $this->apiAdapter->flushCacheForEntities($falconTags);
    }

    /**
     *  Get available cache types
     *
     * @return string[]
     */
    private function getAvailableCacheTypes(): array
    {
        return ['Product', 'Category'];
    }
}
