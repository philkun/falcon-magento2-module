<?php
declare(strict_types=1);

namespace Deity\CmsApi\Test\Api;

use Deity\CmsApi\Api\Data\BlockInterface;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class GetStaticBlockDataTest
 *
 * @package Deity\CmsApi\Test\Api
 */
class GetStaticBlockDataTest extends WebapiAbstract
{
    /**
     * Service constants
     */
    private const RESOURCE_PATH = '/V1/falcon/cms/blocks/:identifier';

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CmsApi/Test/_files/blocks.php
     */
    public function testExecute()
    {
        $this->_markTestAsRestOnly();

        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(
                    ':identifier',
                    'enabled_block',
                    self::RESOURCE_PATH
                ),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET
            ],
        ];

        $blockInfo = $this->_webApiCall($serviceInfo, []);

        $this->assertEquals(
            '<h1>Enabled Block</h1>',
            $blockInfo[BlockInterface::CONTENT],
            'content should match'
        );
    }
}
