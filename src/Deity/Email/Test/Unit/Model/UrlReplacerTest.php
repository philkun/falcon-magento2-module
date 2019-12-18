<?php

namespace Deity\Email\Model;

use Magento\Framework\App\Config;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Url;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UrlReplacerTest extends TestCase
{

    /**
     * @var UrlReplacer
     */
    private $urlReplacer;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var Config | MockObject
     */
    private $scopeConfig;

    /**
     * @var Url | MockObject
     */
    private $urlModel;

    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->scopeConfig = $this->createPartialMock(
            Config::class,
            ['getValue']
        );

        $this->urlModel = $this->createPartialMock(
            Url::class,
            ['getBaseUrl']
        );

        $this->urlReplacer = $this->objectManager->getObject(
            UrlReplacer::class,
            [
                'scopeConfig' => $this->scopeConfig,
                'urlModel' => $this->urlModel
            ]
        );
    }

    public function testReplaceLinkToFalconWhenEmptyConfig()
    {
        $this->urlModel
            ->expects($this->any())
            ->method('getBaseUrl')
            ->will($this->returnValue('http://magento.local/'));

        $this->scopeConfig
            ->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue(''));

        $testUrl = 'http://magento.local/customer/account';

        $this->assertEquals(
            $testUrl,
            $this->urlReplacer->replaceLinkToFalcon($testUrl),
            'No replacement should happen if falcon URL is not specified'
        );
    }

    public function testReplaceLinkToFalcon()
    {
        $this->urlModel
            ->expects($this->any())
            ->method('getBaseUrl')
            ->will($this->returnValue('http://magento.local/'));

        $this->scopeConfig
            ->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('http://falcon.local/'));

        $testUrl = 'http://magento.local/customer/account';

        $this->assertEquals(
            "http://falcon.local/customer/account",
            $this->urlReplacer->replaceLinkToFalcon($testUrl),
            'Domain and protocol should be replaced'
        );
    }

    public function testReplaceLinkToFalconStaticUrl()
    {
        $this->urlModel
            ->expects($this->any())
            ->method('getBaseUrl')
            ->will($this->returnValue('http://magento.local/'));

        $this->scopeConfig
            ->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue('http://falcon.local/'));

        $testUrl = 'http://magento.local/pub/catalog/image';

        $this->assertEquals(
            $testUrl,
            $this->urlReplacer->replaceLinkToFalcon($testUrl),
            'Static resources urls should not be affected'
        );

        $testUrl = 'http://magento.local/static/asset/catalog/image';

        $this->assertEquals(
            $testUrl,
            $this->urlReplacer->replaceLinkToFalcon($testUrl),
            'Static resources urls should not be affected'
        );
    }
}
