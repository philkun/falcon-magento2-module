<?php
declare(strict_types=1);

namespace Deity\BaseApi\Test\Api;

use Deity\BaseApi\Api\Data\ConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class ConfigApiTest
 *
 * @package Deity\BaseApi\Test\Api
 */
class ConfigApiTest extends WebapiAbstract
{
    const CONFIG_API_ENDPOINT = '/V1/falcon/config';

    public function testConfigFields()
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::CONFIG_API_ENDPOINT,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET
            ]
        ];
        $configData =  $this->_webApiCall($serviceInfo);

        /** @var StoreManagerInterface $storeManager */
        $storeManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create(StoreManagerInterface::class);

        $this->assertEquals('5.0.1', $configData[ConfigInterface::VERSION_KEY], 'Module version should match');
        $this->assertEquals(
            $storeManager->getDefaultStoreView()->getCode(),
            $configData[ConfigInterface::STORE_CODE_KEY],
            'Default store code should match'
        );
    }
}
