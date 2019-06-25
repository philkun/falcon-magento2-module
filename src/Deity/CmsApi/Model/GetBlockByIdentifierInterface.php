<?php
declare(strict_types=1);

namespace Deity\CmsApi\Model;

use Magento\Cms\Api\Data\BlockInterface;

/**
 * Interface GetBlockByIdentifierInterface
 * Interface introduced to cover back-compatibility with Magento 2.2
 *
 * @package Deity\CmsApi\Model
 */
interface GetBlockByIdentifierInterface
{
    /**
     * Get block by identifier for given storeId
     *
     * @param string $identifier
     * @param int $storeId
     * @return BlockInterface
     */
    public function execute(string $identifier, int $storeId): BlockInterface;
}
