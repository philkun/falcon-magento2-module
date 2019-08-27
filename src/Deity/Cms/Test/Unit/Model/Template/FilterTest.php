<?php

namespace Deity\Cms\Model\Template;

use Magento\Framework\App\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State;
use Magento\Framework\Url;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Asset\Repository;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Layout;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\View\Model\Layout\Merge;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class FilterTest
 *
 * @package Deity\Cms\Model\Template
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class FilterTest extends TestCase
{

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var Layout | MockObject
     */
    private $layout;

    /**
     * @var LayoutFactory | MockObject
     */
    private $layoutFactory;

    /**
     * @var State | MockObject
     */
    private $appState;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var Repository | MockObject
     */
    private $assetRepo;

    /**
     * @var UrlInterface | MockObject
     */
    private $urlModel;

    /**
     * @var StoreManager | MockObject
     */
    private $storeManager;

    /**
     * @var ScopeConfigInterface | MockObject
     */
    private $scopeConfig;

    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->layout = $this->createPartialMock(
            Layout::class,
            ['createBlock']
        );

        $this->layoutFactory = $this->createPartialMock(
            LayoutFactory::class,
            ['create']
        );

        $this->assetRepo = $this->createPartialMock(
            Repository::class,
            ['getUrlWithParams']
        );

        $this->appState = $this->createPartialMock(
            State::class,
            ['getAreaCode']
        );

        $this->scopeConfig = $this->createPartialMock(
            Config::class,
            ['getValue']
        );

        $this->urlModel = $this->createPartialMock(
            Url::class,
            ['getUrl']
        );

        $this->storeManager = $this->createPartialMock(
            StoreManager::class,
            ['getStore']
        );

        $this->filter = $this->objectManager->getObject(
            Filter::class,
            [
                'layout' => $this->layout,
                'layoutFactory' => $this->layoutFactory,
                'appState' => $this->appState,
                'assetRepo' => $this->assetRepo,
                'scopeConfig' => $this->scopeConfig,
                'urlModel' => $this->urlModel,
                'storeManager' => $this->storeManager
            ]
        );
    }

    public function testStoreDirective()
    {
        $sampleConstruction = [
            0 => '{{store url=""}}',
            1 => 'store',
            2 => ' url='
        ];

        $pathToFalconFrontend = 'https://falcon.frontend/';

        $store = $this->createPartialMock(
            Store::class,
            ['getBaseUrl']
        );

        $store->expects($this->any())
            ->method('getBaseUrl')
            ->will($this->returnValue('https://magento.frontend/'));

        $this->storeManager
            ->expects($this->any())
            ->method('getStore')
            ->will($this->returnValue($store));

        $this->urlModel
            ->expects($this->any())
            ->method('getUrl')
            ->will($this->returnValue('https://magento.frontend/some-page'));

        $this->scopeConfig
            ->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue($pathToFalconFrontend));

        $renderedContent = $this->filter->storeDirective($sampleConstruction);
        $this->assertEquals(
            $pathToFalconFrontend . 'some-page',
            $renderedContent,
            'View content should be omitted'
        );
    }

    public function testViewDirective()
    {
        $sampleConstruction = [
            0 => '{{view url=\'Magento_Customer/images/icn_checkout.png\'}}',
            1 => 'view',
            2 => ' url=\'Magento_Customer/images/icn_checkout.png\''
        ];

        $this->assetRepo
            ->expects($this->any())
            ->method('getUrlWithParams')
            ->will($this->returnValue('any-asset-link'));

        $renderedContent = $this->filter->viewDirective($sampleConstruction);
        $this->assertEquals(
            '',
            $renderedContent,
            'View content should be omitted'
        );
    }

    public function testLayoutDirective()
    {
        $sampleConstruction = [
            0 => '{{layout handle="sales_email_order_creditmemo_items"}}',
            1 => 'layout',
            2 => ' handle="sales_email_order_creditmemo_items"'
        ];

        $layout = $this->createPartialMock(
            Layout::class,
            ['getOutput', 'getUpdate', 'generateXml', 'generateElements']
        );

        $processor = $this->createPartialMock(
            Merge::class,
            ['addHandle', 'load']
        );
        $processor->expects($this->any())
            ->method('addHandle')
            ->will($this->returnSelf());

        $layout->expects($this->any())
            ->method('getUpdate')
            ->will($this->returnValue($processor));
        $layout->expects($this->any())
            ->method('getOutput')
            ->will($this->returnValue('html-rendered-from-layout'));

        $this->layoutFactory
            ->expects($this->any())
            ->method('create')
            ->will($this->returnValue($layout));

        $this->appState->expects($this->any())
            ->method('getAreaCode')
            ->will($this->returnValue('frontend'));

        $renderedContent = $this->filter->layoutDirective($sampleConstruction);
        $this->assertEquals(
            '',
            $renderedContent,
            'Layout content should be omitted'
        );
    }

    /**
     * phpcs:disable
     */
    public function testBlockDirective()
    {
        $sampleConstruction = [
            0 => '{{block class=\'Magento\\Framework\\View\\Element\\Template\' template=\'Magento_Sales::path/to/template.phtml\'}}',
            1 => 'block',
            2 => ' class=\'Magento\\Framework\\View\\Element\\Template\' template=\'Magento_Sales::path/to/template.phtml\''
        ];

        $blockInstance = $this->createPartialMock(
            Template::class,
            ['setBlockParams', 'setDataUsingMethod', 'toHtml']
        );
        $blockInstance->expects($this->any())
            ->method('toHtml')
            ->will($this->returnValue('non-empty-block'));

        $this->layout->expects($this->any())
            ->method('createBlock')
            ->will($this->returnValue($blockInstance));

        $renderedContent = $this->filter->blockDirective($sampleConstruction);
        $this->assertEquals(
            '',
            $renderedContent,
            'Block content should be omitted'
        );
    }

    public function testCssDirective()
    {
        $sampleConstruction = [
            0 => '{{css file="css/filename.css"}}',
            1 => 'css',
            2 => ' file="css/filename.css"'
        ];


        $renderedContent = $this->filter->cssDirective($sampleConstruction);
        $this->assertEquals(
            '',
            $renderedContent,
            'Css include content should be omitted'
        );
    }

    public function testTemplateDirective()
    {
        $sampleConstruction = [
            0 => '{{template config_path="design/email/header_template" }}',
            1 => 'template',
            2 => ' config_path="design/email/header_template"'
        ];

        $renderedContent = $this->filter->templateDirective($sampleConstruction);
        $this->assertEquals(
            '',
            $renderedContent,
            'Template content should be omitted'
        );
    }

    public function testWidgetDirective()
    {
        $sampleConstruction = [
            0 => '{{widget  type="Magento\CatalogWidget\Block\Product\ProductsList" products_count="5" template="product/widget/content/grid.phtml"}}',
            1 => 'widget',
            2 => ' type="Magento\CatalogWidget\Block\Product\ProductsList" products_count="5" template="product/widget/content/grid.phtml"'
        ];

        $renderedContent = $this->filter->widgetDirective($sampleConstruction);
        $this->assertEquals(
            '',
            $renderedContent,
            'Widget content should be omitted'
        );
    }
}

