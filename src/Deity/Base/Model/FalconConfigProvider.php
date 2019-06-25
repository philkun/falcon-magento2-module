<?php
declare(strict_types=1);

namespace Deity\Base\Model;

use Deity\BaseApi\Api\Data\ConfigInterface;
use Deity\BaseApi\Api\Data\ConfigInterfaceFactory;
use Deity\BaseApi\Api\FalconConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class FalconConfigProvider
 *
 * @package Deity\Base\Model
 */
class FalconConfigProvider implements FalconConfigProviderInterface
{

    const DEITY_API_VERSION_CONFIG_PATH = 'falcon/general/api_version';

    const DEITY_FALCON_FRONTEND_URL = 'falcon/frontend/url';

    /**
     * @var ConfigInterfaceFactory
     */
    private $falconConfigFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * FalconConfigProvider constructor.
     *
     * @param ConfigInterfaceFactory $falconConfigFactory
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ConfigInterfaceFactory $falconConfigFactory,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->falconConfigFactory = $falconConfigFactory;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get Falcon Config Interface
     *
     * @return \Deity\BaseApi\Api\Data\ConfigInterface
     */
    public function getConfig(): ConfigInterface
    {
        $defaultStoreCode = $this->storeManager->getDefaultStoreView()->getCode();

        $version = $this->scopeConfig->getValue(
            self::DEITY_API_VERSION_CONFIG_PATH,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );

        /** @var ConfigInterface $configObject */
        $configObject = $this->falconConfigFactory->create(
            [
                ConfigInterface::VERSION_KEY => $version,
                ConfigInterface::STORE_CODE_KEY => $defaultStoreCode
            ]
        );

        return $configObject;
    }
}
