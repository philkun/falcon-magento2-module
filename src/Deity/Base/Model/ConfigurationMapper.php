<?php
declare(strict_types=1);

namespace Deity\Base\Model;

use Deity\BaseApi\Model\ConfigurationMapperInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Class ConfigurationMapper
 *
 * @package Deity\Base\Model
 */
class ConfigurationMapper implements ConfigurationMapperInterface
{

    const FALCON_CONFIGURATION_PREFIX = 'falcon/configuration/';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * ConfigurationMapper constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Process input values. Return array of magento configuration fields with values.
     *
     * @param \Deity\BaseApi\Api\Data\InputConfigInterface[] $inputConfigList
     * @return array
     * @throws CouldNotSaveException
     */
    public function processConfigurationInput(array $inputConfigList): array
    {
        $aggregatedException = new CouldNotSaveException(
            __('Could not save the configuration, please double check provided configuration keys')
        );
        $response = [];
        foreach ($inputConfigList as $inputConfig) {
            $magentoConfigPath = (string)$this->scopeConfig->getValue(
                self::FALCON_CONFIGURATION_PREFIX . $inputConfig->getName()
            );
            if ($magentoConfigPath === '') {
                $aggregatedException->addError(
                    __('Given configuration key does not exist: %1', $inputConfig->getName())
                );
            }

            $response[$magentoConfigPath] = $inputConfig->getValue();
        }

        if ($aggregatedException->wasErrorAdded()) {
            throw $aggregatedException;
        }

        return $response;
    }
}
