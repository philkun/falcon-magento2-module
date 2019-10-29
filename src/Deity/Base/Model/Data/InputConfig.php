<?php
declare(strict_types=1);

namespace Deity\Base\Model\Data;

use Deity\BaseApi\Api\Data\InputConfigInterface;

/**
 * Class InputConfig
 *
 * @package Deity\Base\Model\Data
 */
class InputConfig implements InputConfigInterface
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $value;

    /**
     * Get configuration name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get configuration value
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return InputConfigInterface
     */
    public function setName(string $name): InputConfigInterface
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return $this
     */
    public function setValue(string $value): InputConfigInterface
    {
        $this->value = $value;
        return $this;
    }
}
