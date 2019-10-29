<?php
declare(strict_types=1);

namespace Deity\Base\Model;

use Deity\BaseApi\Api\Data\InputConfigInterface;
use Deity\BaseApi\Api\FalconConfigRepositoryInterface;
use Deity\BaseApi\Model\ConfigurationMapperInterface;
use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\ScopeInterface;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Class FalconConfigRepository
 *
 * @package Deity\Base\Model
 */
class FalconConfigRepository implements FalconConfigRepositoryInterface
{

    /**
     * @var ConfigurationMapperInterface
     */
    private $configurationMapper;

    /**
     * @var Config
     */
    private $configResource;

    /**
     * @var TypeListInterface
     */
    private $cacheList;

    /**
     * FalconConfigRepository constructor.
     * @param ConfigurationMapperInterface $configurationMapper
     * @param Config $configResource
     * @param TypeListInterface $cacheList
     */
    public function __construct(
        ConfigurationMapperInterface $configurationMapper,
        Config $configResource,
        TypeListInterface $cacheList
    ) {
        $this->configurationMapper = $configurationMapper;
        $this->configResource = $configResource;
        $this->cacheList = $cacheList;
    }

    /**
     * Save Falcon specific configuration
     *
     * @param InputConfigInterface[] $inputConfigList
     * @return bool
     *
     * @throws CouldNotSaveException
     */
    public function saveConfiguration(array $inputConfigList): bool
    {

        $mappedConfigData = $this->configurationMapper->processConfigurationInput($inputConfigList);

        if (empty($mappedConfigData)) {
            return false;
        }

        foreach ($mappedConfigData as $configPath => $configValue) {
            $this->configResource->saveConfig($configPath, $configValue, ScopeInterface::SCOPE_DEFAULT, 0);
        }

        $this->cacheList->cleanType('config');

        return true;
    }
}
