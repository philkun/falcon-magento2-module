<?php
declare(strict_types=1);

namespace Deity\CustomerApi\Test\Api;

use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class AccountManagementTest
 *
 * @package Deity\CustomerApi\Test\Api
 */
class AccountManagementTest extends WebapiAbstract
{
    private const VALIDATE_TOKEN_PATH = "/V1/falcon/customers/:customerId/password/resetLinkToken/:passwordToken";

    /**
     * @magentoApiDataFixture Magento/Customer/_files/customer_rp_token.php
     */
    public function testValidateResetPasswordLinkToken()
    {
        $path = str_replace(':passwordToken', '8ed8677e6c79e68b94e61658bd756ea5', self::VALIDATE_TOKEN_PATH);
        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':customerId', 0, $path),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET
            ],
        ];
        $this->assertTrue($this->_webApiCall($serviceInfo));
    }
}
