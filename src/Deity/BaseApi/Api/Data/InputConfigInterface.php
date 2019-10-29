<?php
declare(strict_types=1);

namespace Deity\BaseApi\Api\Data;

/**
 * Interface InputConfigInterface
 *
 * @package Deity\BaseApi\Api\Data
 */
interface InputConfigInterface
{
    const CONFIG_NAME = 'name';
    const CONFIG_VALUE = 'value';

    /**
     * Get configuration name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get configuration value
     *
     * @return string
     */
    public function getValue(): string;

    /**
     * Set name
     *
     * @param string $name
     * @return InputConfigInterface
     */
    public function setName(string $name): InputConfigInterface;

    /**
     * Set value
     *
     * @param string $value
     * @return $this
     */
    public function setValue(string $value): InputConfigInterface;
}
