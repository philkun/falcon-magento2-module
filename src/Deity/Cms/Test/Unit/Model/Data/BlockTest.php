<?php

namespace Deity\Cms\Model\Data;

use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class BlockTest
 *
 * @package Deity\Cms\Model\Data
 */
class BlockTest extends TestCase
{

    /**
     * @var Block
     */
    private $block;

    /**
     * @var string
     */
    private $content;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->content = 'test-static-block-content';

        $this->block = $this->objectManager->getObject(
            Block::class,
            [
                'content' => $this->content
            ]
        );
    }

    public function testGetContent()
    {
        $content = $this->block->getContent();
        $this->assertEquals(
            $this->content,
            $content,
            'Content should be the same'
        );
    }
}
