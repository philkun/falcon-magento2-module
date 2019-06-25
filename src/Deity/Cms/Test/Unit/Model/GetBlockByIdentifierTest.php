<?php

namespace Deity\Cms\Model;

use Magento\Cms\Model\BlockFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\Cms\Model\ResourceModel\Block;
use Magento\Cms\Model\Block as BlockInstance;

class GetBlockByIdentifierTest extends TestCase
{

    /**
     * @var GetBlockByIdentifier
     */
    private $getBlockByIdentifier;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var Block | MockObject
     */
    private $blockResource;

    /**
     * @var BlockFactory | MockObject
     */
    private $blockFactory;

    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);
        $this->blockResource = $this->createPartialMock(
            Block::class,
            ['load']
        );

        $this->blockFactory = $this->createPartialMock(
            BlockFactory::class,
            ['create']
        );

        $this->getBlockByIdentifier = $this->objectManager->getObject(
            GetBlockByIdentifier::class,
            [
                'blockFactory' => $this->blockFactory,
                'blockResource' => $this->blockResource
            ]
        );
    }

    public function testExecute()
    {
        $blockIdentity = 'any';
        $blockInstance = $this->createMock(BlockInstance::class);
        $blockInstance
            ->expects($this->once())
            ->method('getId')
            ->will(self::returnValue(1));

        $blockInstance
            ->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue($blockIdentity));

        $this->blockFactory
            ->expects($this->once())
            ->method('create')
            ->will($this->returnValue($blockInstance));

        $this->blockResource
            ->expects($this->once())
            ->method('load')
            ->will($this->returnArgument(1));

        $blockObject = $this->getBlockByIdentifier->execute($blockIdentity, 1);
        $this->assertEquals(
            $blockIdentity,
            $blockObject->getIdentifier(),
            'Block identifier should match'
        );
    }

    public function testExecuteThrowsException()
    {
        $this->expectException(NoSuchEntityException::class);
        $blockIdentity = 'any';
        $blockInstance = $this->createMock(BlockInstance::class);

        $this->blockFactory
            ->expects($this->once())
            ->method('create')
            ->will($this->returnValue($blockInstance));

        $this->blockResource
            ->expects($this->once())
            ->method('load')
            ->will($this->returnArgument(1));

        $this->getBlockByIdentifier->execute($blockIdentity, 1);
    }
}
