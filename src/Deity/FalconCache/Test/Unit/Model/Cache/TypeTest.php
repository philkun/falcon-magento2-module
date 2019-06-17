<?php

namespace Deity\FalconCache\Model\Cache;

use Deity\FalconCache\Model\CacheManagement;
use Magento\Framework\App\Cache\Type\FrontendPool;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class TypeTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var Type
     */
    private $cacheType;

    /**
     * @var CacheManagement | \PHPUnit_Framework_MockObject_MockObject
     */
    private $cacheManagement;

    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $frontendPool = $this->getMockBuilder(FrontendPool::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cacheManagement = $this->createPartialMock(
            CacheManagement::class,
            ['cleanFalconCache', 'cleanFalconCacheForTags']
        );

        $this->cacheType = $this->objectManager->getObject(
            Type::class,
            [
                'cacheFrontendPool' => $frontendPool,
                'cacheManagement' => $this->cacheManagement
            ]
        );
    }

    /**
     * @covers \Deity\FalconCache\Model\Cache\Type::getTag
     */
    public function testGetTag()
    {
        $tag = $this->cacheType->getTag();
        $this->assertEquals(Type::CACHE_TAG, $tag, 'Cache tag should match');
    }

    /**
     * @covers \Deity\FalconCache\Model\Cache\Type::save
     */
    public function testSave()
    {
        $result = $this->cacheType->save('any-data-key', 'any-identifier');
        $this->assertEquals(true, $result, 'save function is not used should always return true');
    }

    /**
     * @covers \Deity\FalconCache\Model\Cache\Type::load
     */
    public function testLoad()
    {
        $result = $this->cacheType->load('any-identifier');
        $this->assertEquals(false, $result, 'load function is not used should always return false');
    }

    /**
     * @covers \Deity\FalconCache\Model\Cache\Type::test
     */
    public function testTest()
    {
        $result = $this->cacheType->test('any-identifier');
        $this->assertEquals(false, $result, 'test function is not used should always return false');
    }

    /**
     * @covers \Deity\FalconCache\Model\Cache\Type::remove
     */
    public function testRemove()
    {
        $result = $this->cacheType->remove('any-identifier');
        $this->assertEquals(true, $result, 'remove function is not used should always return true');
    }

    /**
     * @covers \Deity\FalconCache\Model\Cache\Type::clean
     */
    public function testCleanCleanModeAll()
    {
        $this->cacheManagement
            ->expects($this->once())
            ->method('cleanFalconCache')
            ->will($this->returnValue(true));

        $this->cacheManagement
            ->expects($this->never())
            ->method('cleanFalconCacheForTags');

        $cleanResult = $this->cacheType->clean();

        $this->assertEquals(true, $cleanResult, 'clean result should be true');
    }

    /**
     * @covers \Deity\FalconCache\Model\Cache\Type::clean
     */
    public function testCleanCleanModeByTag()
    {
        $this->cacheManagement
            ->expects($this->never())
            ->method('cleanFalconCache');

        $this->cacheManagement
            ->expects($this->once())
            ->method('cleanFalconCacheForTags')
            ->will($this->returnValue(true));

        $cleanResult = $this->cacheType->clean(\Zend_Cache::CLEANING_MODE_MATCHING_TAG);

        $this->assertEquals(true, $cleanResult, 'clean result should be true');
    }
}
