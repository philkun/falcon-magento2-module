<?php

namespace Deity\FalconCache\Test\Unit\Model;

use Deity\FalconCache\Model\CacheManagement;
use Deity\FalconCache\Model\CacheTagMapper;
use Deity\FalconCache\Model\FalconApiAdapter;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * Class CacheManagementTest
 *
 * @package Deity\FalconCache\Test\Unit\Model
 */
class CacheManagementTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var FalconApiAdapter | \PHPUnit_Framework_MockObject_MockObject
     */
    private $apiAdapter;

    /**
     * @var CacheManagement
     */
    private $cacheManager;

    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->apiAdapter = $this->createPartialMock(
            FalconApiAdapter::class,
            ['getError', 'flushCacheForGivenType', 'flushCacheForEntities']
        );

        $cacheTagMapper = new CacheTagMapper();

        $this->cacheManager = $this->objectManager->getObject(
            CacheManagement::class,
            [
                'apiAdapter' => $this->apiAdapter,
                'cacheTagMapper' => $cacheTagMapper
            ]
        );
    }

    /**
     * @covers \Deity\FalconCache\Model\CacheManagement::cleanFalconCache
     */
    public function testCleanFalconCache()
    {
        $this->apiAdapter
            ->expects($this->any())
            ->method('flushCacheForGivenType')
            ->will($this->returnValue(true));

        $result = $this->cacheManager->cleanFalconCache();

        $this->assertEquals(true, $result, 'Clean cache call should match');
    }

    /**
     * @param $tags
     * @covers \Deity\FalconCache\Model\CacheManagement::cleanFalconCacheForTags
     * @dataProvider getMagentoCacheTags
     */
    public function testCleanFalconCacheForTags($tags)
    {
        $this->apiAdapter
            ->expects($this->any())
            ->method('flushCacheForEntities')
            ->will($this->returnValue(true));

        $result = $this->cacheManager->cleanFalconCacheForTags($tags);

        $this->assertEquals(true, $result, 'Clean cache call should match');
    }

    /**
     * Data provider
     *
     * @return \Generator
     */
    public function getMagentoCacheTags()
    {
        yield 'products-tag' => [['cat_p_2', 'cat_p']];
        yield 'category-product-tag' => [['cat_c_20', 'cat_p_2']];
        yield 'categories-tag' => [['cat_c_p_20', 'cat_c']];
        yield 'mixed' => [['cat_p_2', 'cat_p', 'cat_c_p_20', 'cat_c']];
    }
}
