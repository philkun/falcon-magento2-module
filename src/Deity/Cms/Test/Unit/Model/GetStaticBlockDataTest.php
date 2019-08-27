<?php

namespace Deity\Cms\Model;

use Deity\Cms\Model\Data\Block;
use Deity\Cms\Model\Template\Filter;
use Deity\CmsApi\Api\Data\BlockInterface;
use Deity\CmsApi\Api\Data\BlockInterfaceFactory;
use Magento\Cms\Model\Block as BlockAlias;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class GetStaticBlockDataTest
 *
 * @package Deity\Cms\Model
 */
class GetStaticBlockDataTest extends TestCase
{

    /**
     * @var GetStaticBlockData
     */
    private $getStaticBlockContent;

    /**
     * @var GetBlockByIdentifier | MockObject
     */
    private $getBlockByIdentifier;

    /**
     * @var BlockInterfaceFactory | MockObject
     */
    private $blockFactory;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var StoreManager | MockObject
     */
    private $storeManager;

    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->getBlockByIdentifier = $this->createPartialMock(
            GetBlockByIdentifier::class,
            ['execute']
        );

        $storeObject = $this->createPartialMock(
            Store::class,
            ['getId']
        );
        $storeObject
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));
        $this->storeManager = $this->createPartialMock(
            StoreManager::class,
            ['getStore']
        );

        $this->blockFactory = $this->createPartialMock(
            BlockInterfaceFactory::class,
            ['create']
        );

        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->will($this->returnValue($storeObject));

        $filterEmulate = $this->createMock(Filter::class);
        $filterEmulate->expects($this->any())
            ->method('filter')
            ->will($this->returnArgument(0));

        $this->getStaticBlockContent = $this->objectManager->getObject(
            GetStaticBlockData::class,
            [
                'storeManager' => $this->storeManager,
                'getBlockByIdentifier' => $this->getBlockByIdentifier,
                'filterEmulate' => $filterEmulate,
                'blockFactory' => $this->blockFactory
            ]
        );
    }

    public function testExecute()
    {
        $testIdentifier = 'any-block';
        $testBlockContent = 'any-content';

        $block = $this->createMock(BlockAlias::class);

        $block->expects($this->any())
            ->method('getContent')
            ->will($this->returnValue($testBlockContent));
        $this->getBlockByIdentifier
            ->expects($this->any())
            ->method('execute')
            ->will($this->returnValue($block));

        $blockObject = $this->createMock(Block::class);
        $blockObject->expects($this->any())
            ->method('getContent')
            ->will($this->returnValue($testBlockContent));

        $this->blockFactory
            ->expects($this->any())
            ->method('create')
            ->will($this->returnValue($blockObject));

        /** @var \Deity\Cms\Model\Data\Block $blockObject */
        $blockObject = $this->getStaticBlockContent->execute($testIdentifier);
        $this->assertEquals(
            $testBlockContent,
            $blockObject->getContent(),
            'Service should return block content'
        );
    }
}
