<?php

namespace Deity\FalconCache\Model;

use Magento\Framework\App\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class ConfigProviderTest extends TestCase
{

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var ScopeConfigInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $scopeConfig;

    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->scopeConfig = $this->createPartialMock(Config::class, ['getValue']);

        $this->configProvider = $this->objectManager->getObject(
            ConfigProvider::class,
            [
                'scopeConfig' => $this->scopeConfig
            ]
        );
    }

    /**
     * @covers \Deity\FalconCache\Model\ConfigProvider::getFalconApiCacheUrl
     */
    public function testGetFalconApiCacheUrl()
    {

        $cacheCleanUrl = 'http://localhost/cache-key';
        $this->scopeConfig
            ->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue($cacheCleanUrl));

        $cacheApiUrl = $this->configProvider->getFalconApiCacheUrl();
        $this->assertEquals($cacheCleanUrl, $cacheApiUrl, 'Cache url should match');
    }
}
