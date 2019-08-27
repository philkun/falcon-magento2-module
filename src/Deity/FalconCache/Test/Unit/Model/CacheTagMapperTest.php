<?php

namespace Deity\FalconCache\Model;

use Deity\FalconCacheApi\Model\CacheTagMapperInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * Class CacheTagMapperTest
 *
 * @package Deity\FalconCache\Model
 */
class CacheTagMapperTest extends TestCase
{

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var CacheTagMapper
     */
    private $cacheTagMapper;

    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->cacheTagMapper = $this->objectManager->getObject(
            CacheTagMapper::class
        );
    }

    /**
     * @covers \Deity\FalconCache\Model\CacheTagMapper::getAvailableCacheTypes
     */
    public function testGetAvailableCacheTypes()
    {
        $availableTags = $this->cacheTagMapper->getAvailableCacheTypes();
        $this->assertTrue(
            in_array(CacheTagMapperInterface::CATEGORY_CACHE_TAG, $availableTags),
            'Category cache tag is supported'
        );

        $this->assertTrue(
            in_array(CacheTagMapperInterface::PRODUCT_CACHE_TAG, $availableTags),
            'Product cache tag is supported'
        );
    }

    /**
     * @covers \Deity\FalconCache\Model\CacheTagMapper::mapMagentoCacheTagsToFalconApiCache
     * @param array $magentoTags
     * @param array $expected
     * @dataProvider getMagentoCacheTagsSamples
     */
    public function testMapMagentoCacheTagsToFalconApiCache(array $magentoTags, array $expected)
    {
        $mappedTags = $this->cacheTagMapper->mapMagentoCacheTagsToFalconApiCache($magentoTags);
        $this->assertTrue(
            $this->arraysAreSimilar($mappedTags, $expected),
            'Cache tags should match'
        );
    }

    /**
     * Compare two arrays
     *
     * @param $tagsReturnedArray
     * @param $tagsExpectedArray
     * @return bool
     */
    private function arraysAreSimilar($tagsReturnedArray, $tagsExpectedArray): bool
    {
        if (count($tagsReturnedArray) !== count($tagsExpectedArray)) {
            return false;
        }

        foreach ($tagsReturnedArray as $tagReturned) {
            $match = false;
            $cacheTag = \key($tagReturned);
            foreach ($tagsExpectedArray as $tagExpected) {
                if (isset($tagExpected[$cacheTag]) && $tagReturned[$cacheTag] === $tagExpected[$cacheTag]) {
                    $match = true;
                    break;
                }
            }
            if ($match === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @covers \Deity\FalconCache\Model\CacheTagMapper::filterMagentoCacheTags
     * @param array $magentoTags
     * @param array $expected
     * @dataProvider getExcessiveMagentoTags
     */
    public function testFilterMagentoCacheTags(array $magentoTags, array $expected)
    {
        $filteredTags = $this->cacheTagMapper->filterMagentoCacheTags($magentoTags);
        $this->assertEmpty(
            array_diff($filteredTags, $expected),
            'Filtered cache tags should match'
        );
    }

    /**
     * Data provider
     *
     * @return \Generator
     */
    public function getMagentoCacheTagsSamples()
    {
        yield 'product-tag' => [['cat_p_2'], [['type' => 'Product', 'id' => 2]]];
        yield 'category-tag' => [['cat_c_20'], [['type' =>'Category', 'id' => 20]]];
        yield 'category-product-tag' => [['cat_c_p_20'], [['type' => 'Category', 'id' => 20]]];
        yield 'all-products-tag' => [['cat_p'], [['type' => 'Product']]];
        yield 'all-categories-tag' => [['cat_c'], [['type' => 'Category']]];
    }

    /**
     * Data provider
     *
     * @return \Generator
     */
    public function getExcessiveMagentoTags()
    {
        yield 'products-tag' => [['cat_p_2', 'cat_p'], ['cat_p']];
        yield 'category-product-tag' => [['cat_c_20', 'cat_p_2'], ['cat_c_20', 'cat_p_2']];
        yield 'categories-tag' => [['cat_c_p_20', 'cat_c'], ['cat_c']];
        yield 'mixed' => [['cat_p_2', 'cat_p', 'cat_c_p_20', 'cat_c'], ['cat_c', 'cat_p']];
    }
}
