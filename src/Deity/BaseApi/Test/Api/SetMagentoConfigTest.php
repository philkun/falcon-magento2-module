<?php
declare(strict_types=1);

namespace Deity\BaseApi\Test\Api;

use Magento\Framework\App\Config\ReinitableConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class SetMagentoConfigTest
 *
 * @package Deity\BaseApi\Test\Api
 */
class SetMagentoConfigTest extends WebapiAbstract
{
    const CONFIG_API_ENDPOINT = '/V1/falcon/config';

    public function testConfigFields()
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::CONFIG_API_ENDPOINT,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_POST
            ]
        ];

        $testFalconUrl = "http://falcon.local/";

        $requestData = [
            "inputConfigList" => [
                ["name" => "url", "value" => $testFalconUrl]
            ]
        ];
        $configSetResponse =  $this->_webApiCall($serviceInfo, $requestData);

        $this->assertTrue($configSetResponse, "response should return true");

        /** @var StoreManagerInterface $storeManager */
        $storeManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create(StoreManagerInterface::class);

        /** @var ReinitableConfigInterface $scopeConfig */
        $scopeConfig = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create(ReinitableConfigInterface::class);
        $store = $storeManager->getDefaultStoreView();
        $store->getCode();
        $scopeConfig->reinit();
        $savedFalconUrl = $scopeConfig->getValue('falcon/frontend/url');

        $this->assertEquals($testFalconUrl, $savedFalconUrl, "Preset falcon value should match");
    }
}
