<?php
declare(strict_types=1);

namespace Deity\BaseApi\Api;

use Magento\Framework\Exception\NotFoundException;

/**
 * Interface FalconConfigRepositoryInterface
 *
 * @package Deity\BaseApi\Api
 */
interface FalconConfigRepositoryInterface
{
    /**
     * Save Falcon specific configuration
     *
     * @param \Deity\BaseApi\Api\Data\InputConfigInterface[] $inputConfigList
     * @return bool
     * @throws NotFoundException
     */
    public function saveConfiguration(array $inputConfigList): bool;
}
