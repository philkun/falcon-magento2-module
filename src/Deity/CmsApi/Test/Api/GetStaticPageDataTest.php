<?php
declare(strict_types=1);

namespace Deity\CmsApi\Test\Api;

use Deity\CmsApi\Api\Data\PageInterface;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class GetStaticPageDataTest
 *
 * @package Deity\CmsApi\Test\Api
 */
class GetStaticPageDataTest extends WebapiAbstract
{
    /**
     * Service constants
     */
    private const RESOURCE_PATH = '/V1/falcon/cms/pages/:pageId';

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CmsApi/Test/_files/pages.php
     */
    public function testExecute()
    {
        $this->_markTestAsRestOnly();

        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(
                    ':pageId',
                    21,
                    self::RESOURCE_PATH
                ),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET
            ],
        ];

        $pageInfo = $this->_webApiCall($serviceInfo, []);

        $this->assertEquals(
            '<h1>Cms Page Design Blank Title</h1>',
            $pageInfo[PageInterface::CONTENT],
            'content should match'
        );

        $this->assertEquals(
            'meta title Cms Page Design Blank',
            $pageInfo[PageInterface::META_TITLE],
            'meta title should match'
        );

        $this->assertEquals(
            'meta description Cms Page Design Blank',
            $pageInfo[PageInterface::META_DESCRIPTION],
            'meta description should match'
        );

        $this->assertEquals(
            'meta keywords Cms Page Design Blank',
            $pageInfo[PageInterface::META_KEYWORDS],
            'meta keywords should match'
        );
    }
}
