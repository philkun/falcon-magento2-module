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
     * InputConfig constructor.
     * @param string $name
     * @param string $value
     */
    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

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
}
