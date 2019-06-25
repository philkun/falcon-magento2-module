<?php

namespace Deity\Cms\Model;

use Deity\Cms\Model\Data\Page;
use Deity\Cms\Model\Template\Filter;
use Deity\CmsApi\Api\Data\PageInterface;
use Deity\CmsApi\Api\Data\PageInterfaceFactory;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Model\PageRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class GetStaticPageDataTest extends TestCase
{

    /**
     * @var GetStaticPageData
     */
    private $getStaticPageData;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var Filter | MockObject
     */
    private $filter;

    /**
     * @var PageRepositoryInterface | MockObject
     */
    private $pageRepository;

    /**
     * @var PageInterfaceFactory | MockObject
     */
    private $pageDataFactory;

    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->pageRepository = $this->createPartialMock(
            PageRepository::class,
            ['getById']
        );

        $this->filter = $this->createPartialMock(
            Filter::class,
            ['filter']
        );

        $this->pageDataFactory = $this->createPartialMock(
            PageInterfaceFactory::class,
            ['create']
        );

        $this->getStaticPageData = $this->objectManager->getObject(
            GetStaticPageData::class,
            [
                'pageRepository' => $this->pageRepository,
                'filter' => $this->filter,
                'pageFactory' => $this->pageDataFactory
            ]
        );
    }

    public function testExecute()
    {
        $testPageId = 27;
        $testPageContent = 'test-page-content';
        $testPageMetaTitle = 'test-page-meta title';
        $testPageMetaDescription = 'test-page meta description';
        $testPageMetaKeyworkds = 'test-page meta keywords';

        $pageInstance = $this->createMock(Page::class);
        $pageInstance
            ->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue($testPageContent));
        $pageInstance
            ->expects($this->once())
            ->method('getMetaTitle')
            ->will($this->returnValue($testPageMetaTitle));

        $pageInstance
            ->expects($this->once())
            ->method('getMetaDescription')
            ->will($this->returnValue($testPageMetaDescription));

        $pageInstance
            ->expects($this->once())
            ->method('getMetaKeywords')
            ->will($this->returnValue($testPageMetaKeyworkds));

        $this->filter
            ->expects($this->once())
            ->method('filter')
            ->will($this->returnValue($testPageContent));

        $this->pageDataFactory
            ->expects($this->once())
            ->method('create')
            ->will($this->returnValue($pageInstance));

        $this->pageRepository
            ->expects($this->any())
            ->method('getById')
            ->will($this->returnValue($this->createPartialMock(\Magento\Cms\Model\Page::class, ['getContent'])));

        $pageData = $this->getStaticPageData->execute($testPageId);

        $this->assertEquals(
            $testPageContent,
            $pageData->getContent(),
            'Returned page content should match'
        );

        $this->assertEquals(
            $testPageMetaTitle,
            $pageData->getMetaTitle(),
            'Returned page meta title should match'
        );

        $this->assertEquals(
            $testPageMetaDescription,
            $pageData->getMetaDescription(),
            'Returned page meta description should match'
        );

        $this->assertEquals(
            $testPageMetaKeyworkds,
            $pageData->getMetaKeywords(),
            'Returned page meta keywords should match'
        );
    }
}
