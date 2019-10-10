<?php
declare(strict_types=1);

namespace Deity\FalconCache\Model;

use Deity\FalconCacheApi\Model\CacheTagMapperInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;

/**
 * Class CacheTagMapper
 *
 * @package Deity\FalconCache\Model
 */
class CacheTagMapper implements CacheTagMapperInterface
{

    /**
     * Get cache tags supported by Falcon
     *
     * @return array
     */
    public function getAvailableCacheTypes(): array
    {
        return [self::CATEGORY_CACHE_TAG, self::PRODUCT_CACHE_TAG];
    }

    /**
     * Map magento cache tags to falcon cache tags
     *
     * @param array $tags
     * @return array
     */
    public function mapMagentoCacheTagsToFalconApiCache(array $tags): array
    {
        $falconTags = [];
        foreach ($tags as $tag) {
            $entityId = $this->getEntityIdFromCacheTag($tag);

            $entityType = $this->getEntityTypeFromCacheTag($tag);

            if (!$entityType) {
                //unsupported entity type
                continue;
            }

            if ($entityId !== 0) {
                $falconTags[] = ['type' => $entityType, 'id' => $entityId];
                continue;
            }

            $falconTags[] = ['type' => $entityType];
        }

        return $falconTags;
    }

    /**
     * Get entity type from magento cache tag
     *
     * @param string $tag
     * @return string
     */
    private function getEntityTypeFromCacheTag(string $tag): string
    {
        $entityType = '';
        if (strpos($tag, Product::CACHE_TAG) === 0) {
            $entityType = self::PRODUCT_CACHE_TAG;
        }

        if (strpos($tag, Category::CACHE_TAG) === 0) {
            $entityType = self::CATEGORY_CACHE_TAG;
        }

        return $entityType;
    }

    /**
     * Filter out magento tags
     *
     * @param array $tags
     * @return array
     */
    public function filterMagentoCacheTags(array $tags): array
    {
        foreach ([Product::CACHE_TAG, Category::CACHE_TAG] as $entityTag) {
            $indexFound = array_search($entityTag, $tags);
            if ($indexFound !== false) {
                $tags = array_filter(
                    $tags,
                    function ($tag) use ($entityTag) {
                        if (strpos($tag, $entityTag) === 0) {
                            return false;
                        }
                        return true;
                    }
                );
                array_push($tags, $entityTag);
            }
        }
        return $tags;
    }

    /**
     * Get entity id from cache tag
     *
     * @param string $tag
     * @return int
     */
    private function getEntityIdFromCacheTag(string $tag): int
    {
        preg_match('/\d+$/', $tag, $matches);
        return (int)($matches[0] ?? 0);
    }
}
