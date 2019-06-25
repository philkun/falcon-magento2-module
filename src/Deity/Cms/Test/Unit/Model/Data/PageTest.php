<?php

namespace Deity\Cms\Model\Data;

use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class PageTest extends TestCase
{

    /**
     * @var Page
     */
    private $page;

    private $content;

    private $metaTitle;

    private $metaDescription;

    private $metaKeywords;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->content = 'test-static-page-content';

        $this->metaTitle = 'test-meta title';

        $this->metaDescription = 'test-meta description';

        $this->metaKeywords = 'test-meta page,static,keywords';

        $this->page = $this->objectManager->getObject(
            Page::class,
            [
                'content' => $this->content,
                'metaTitle' => $this->metaTitle,
                'metaDescription' => $this->metaDescription,
                'metaKeywords' => $this->metaKeywords
            ]
        );
    }

    public function testGetMetaTitle()
    {
        $metaTitle = $this->page->getMetaTitle();
        $this->assertEquals(
            $this->metaTitle,
            $metaTitle,
            'meta title should match'
        );
    }

    public function testGetMetaDescription()
    {
        $metaDesc = $this->page->getMetaDescription();
        $this->assertEquals(
            $this->metaDescription,
            $metaDesc,
            'meta description should match'
        );
    }

    public function testGetMetaKeywords()
    {
        $metaKeywords = $this->page->getMetaKeywords();
        $this->assertEquals(
            $this->metaKeywords,
            $metaKeywords,
            'meta keywords should match'
        );
    }

    public function testGetContent()
    {
        $content = $this->page->getContent();
        $this->assertEquals(
            $this->content,
            $content,
            'page content should match'
        );
    }
}
