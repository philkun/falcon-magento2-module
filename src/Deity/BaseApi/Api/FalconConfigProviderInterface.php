<?php
declare(strict_types=1);

namespace Deity\BaseApi\Api;

use Deity\BaseApi\Api\Data\ConfigInterface;

/**
 * Interface FalconConfigProviderInterface
 *
 * @package Deity\BaseApi\Api
 */
interface FalconConfigProviderInterface
{
    /**
     * Get Falcon Config Interface
     *
     * @return \Deity\BaseApi\Api\Data\ConfigInterface
     */
    public function getConfig(): ConfigInterface;
}
