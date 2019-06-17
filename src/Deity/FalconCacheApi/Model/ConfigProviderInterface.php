<?php
declare(strict_types=1);

namespace Deity\FalconCacheApi\Model;

/**
 * Interface ConfigProviderInterface
 *
 * @package Deity\FalconCacheApi\Model
 */
interface ConfigProviderInterface
{
    /**
     * Get Api
     *
     * @return string
     */
    public function getFalconApiCacheUrl(): string;
}
