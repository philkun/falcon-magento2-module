<?php
declare(strict_types=1);

namespace Deity\BaseApi\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface ConfigInterface
 *
 * @package Deity\BaseApi\Api\Data
 */
interface ConfigInterface extends ExtensibleDataInterface
{

    const VERSION_KEY = 'version';

    const STORE_CODE_KEY = 'store_code';

    /**
     * Get falcon extension version
     *
     * @return string
     */
    public function getVersion(): string;

    /**
     * Get magento default store code
     *
     * @return string
     */
    public function getStoreCode(): string;
}
