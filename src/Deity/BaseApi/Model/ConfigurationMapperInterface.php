<?php
declare(strict_types=1);

namespace Deity\BaseApi\Model;

/**
 * Interface ConfigurationMapperInterface
 *
 * @package Deity\BaseApi\Model
 */
interface ConfigurationMapperInterface
{
    /**
     * Process input values. Return array of magento configuration fields with values.
     *
     * @param \Deity\BaseApi\Api\Data\InputConfigInterface[] $inputConfigList
     * @return array
     */
    public function processConfigurationInput(array $inputConfigList): array;
}
