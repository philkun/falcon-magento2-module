<?php
declare(strict_types=1);

namespace Deity\CmsApi\Api;

use Deity\CmsApi\Api\Data\BlockInterface;

/**
 * Interface GetStaticBlockContentInterface
 *
 * @package Deity\CmsApi\Api
 */
interface GetStaticBlockDataInterface
{
    /**
     * Get content of the static block
     *
     * @param string $identifier
     * @return \Deity\CmsApi\Api\Data\BlockInterface
     */
    public function execute(string $identifier): BlockInterface;
}
