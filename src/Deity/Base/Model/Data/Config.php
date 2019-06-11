<?php
declare(strict_types=1);

namespace Deity\Base\Model\Data;

use Deity\BaseApi\Api\Data\ConfigInterface;
use Deity\BaseApi\Api\Data\ConfigExtensionInterface;
use Deity\BaseApi\Api\Data\ConfigExtensionInterfaceFactory;

/**
 * Class Config
 *
 * @package Deity\Base\Model\Data
 */
class Config implements ConfigInterface
{

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $storeCode;

    /**
     * @var ConfigExtensionInterface
     */
    private $extensionAttributes;

    /**
     * @var ConfigExtensionInterfaceFactory
     */
    private $extensionAttributesFactory;

    /**
     * Config constructor.
     * @param string $version
     * @param string $store_code
     * @param ConfigExtensionInterfaceFactory $configExtensionInterfaceFactory
     */
    public function __construct(
        string $version,
        string $store_code,
        ConfigExtensionInterfaceFactory $configExtensionInterfaceFactory
    ) {
        $this->extensionAttributesFactory = $configExtensionInterfaceFactory;
        $this->version = $version;
        $this->storeCode = $store_code;
    }

    /**
     * Get falcon extension version
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Get magento default store code
     *
     * @return string
     */
    public function getStoreCode(): string
    {
        return $this->storeCode;
    }

    /**
     * Get extension attributes
     *
     * @return \Deity\BaseApi\Api\Data\ConfigExtensionInterface
     */
    public function getExtensionAttributes()
    {
        if (!$this->extensionAttributes) {
            $this->extensionAttributes = $this->extensionAttributesFactory->create(ConfigInterface::class);
        }

        return $this->extensionAttributes;
    }

    /**
     * Set extension attributes
     *
     * @param \Deity\BaseApi\Api\Data\ConfigExtensionInterface $extensionAttributes
     * @return \Deity\BaseApi\Api\Data\ConfigInterface
     */
    public function setExtensionAttributes(ConfigExtensionInterface $extensionAttributes): ConfigInterface
    {
        $this->extensionAttributes = $extensionAttributes;
        return $this;
    }
}
