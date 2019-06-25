<?php
declare(strict_types=1);

namespace Deity\CmsApi\Api;

use Deity\CmsApi\Api\Data\PageInterface;

/**
 * Interface GetStaticPageDataInterface
 *
 * @package Deity\CmsApi\Api
 */
interface GetStaticPageDataInterface
{

    /**
     * Get static page content object
     *
     * @param int $pageId
     * @return \Deity\CmsApi\Api\Data\PageInterface
     */
    public function execute(int $pageId): PageInterface;
}
