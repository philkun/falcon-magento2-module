<?php
declare(strict_types=1);

namespace Deity\Store\Plugin\Service;

use Magento\Customer\Model\AccountManagement;
use Magento\Directory\Helper\Data;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Api\Data\StoreConfigInterface;
use Magento\Store\Api\StoreConfigManagerInterface;
use Magento\Store\Api\Data\StoreConfigExtensionInterface;
use Magento\Store\Api\Data\StoreConfigExtensionFactory;

/**
 * Class StoreConfigManager
 *
 * @package Deity\Store\Plugin\Service
 */
class StoreConfigManager
{

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var StoreConfigExtensionFactory
     */
    private $storeConfigExtensionFactory;

    /**
     *  Extension values that can be fetched directly from magento configuration
     *
     * @var string[]
     */
    private $extensionConfigData = [
        'min_password_length' => AccountManagement::XML_PATH_MINIMUM_PASSWORD_LENGTH,
        'min_password_char_class' => AccountManagement::XML_PATH_REQUIRED_CHARACTER_CLASSES_NUMBER,
        'customer_token_lifetime' => 'oauth/access_token_lifetime/customer',
        'admin_token_lifetime' => 'oauth/access_token_lifetime/admin'
    ];

    /**
     * AfterGetStoreConfigs constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreConfigExtensionFactory $storeConfigExtensionFactory
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreConfigExtensionFactory $storeConfigExtensionFactory
    ) {

        $this->scopeConfig = $scopeConfig;
        $this->storeConfigExtensionFactory = $storeConfigExtensionFactory;
    }

    /**
     * After plugin for getStoreConfigs method
     *
     * @param StoreConfigManagerInterface $subject
     * @param StoreConfigInterface[] $result
     * @return StoreConfigInterface[]
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetStoreConfigs(StoreConfigManagerInterface $subject, $result)
    {
        $optionalZipCodeCountries = $this->scopeConfig->getValue(Data::OPTIONAL_ZIP_COUNTRIES_CONFIG_PATH);
        if (!empty($optionalZipCodeCountries)) {
            $optionalZipCodeCountries = explode(',', $optionalZipCodeCountries);
        }

        foreach ($result as $item) { /** @var StoreConfigInterface $item */
            /** @var StoreConfigExtensionInterface $extensionAttributes */
            $extensionAttributes = $item->getExtensionAttributes();
            if (!$extensionAttributes) {
                $extensionAttributes = $this->storeConfigExtensionFactory->create();
            }
            $extensionAttributes->setOptionalPostCodes($optionalZipCodeCountries);

            foreach ($this->extensionConfigData as $dataKey => $storeConfigPath) {
                $extensionAttributes->setData(
                    $dataKey,
                    $this->scopeConfig->getValue($storeConfigPath)
                );
            }
            $item->setExtensionAttributes($extensionAttributes);
        }

        return $result;
    }
}
