<?php
declare(strict_types=1);

namespace Deity\FalconCache\Model;

use Deity\FalconCacheApi\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class ConfigProvider
 *
 * @package Deity\FalconCache\Model
 */
class ConfigProvider implements ConfigProviderInterface
{
    const DEITY_API_VERSION_CONFIG_PATH = 'falcon/cache/url';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * ConfigProvider constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get Api
     *
     * @return string
     */
    public function getFalconApiCacheUrl(): string
    {
        return (string) $this->scopeConfig->getValue(
            self::DEITY_API_VERSION_CONFIG_PATH,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );
    }
}
