<?php
declare(strict_types=1);

namespace Deity\Cms\Model\Data;

use Deity\CmsApi\Api\Data\BlockExtensionInterface;
use Deity\CmsApi\Api\Data\BlockInterface;
use Magento\Framework\Api\ExtensionAttributesFactory;

/**
 * Class Block
 *
 * @package Deity\Cms\Model\Data
 */
class Block implements BlockInterface
{

    /**
     * @var string
     */
    private $content;

    /**
     * @var BlockExtensionInterface
     */
    private $extensionAttributes;

    /**
     * @var ExtensionAttributesFactory
     */
    private $extensionAttributesFactory;

    /**
     * Block constructor.
     * @param string $content
     * @param ExtensionAttributesFactory $extensionAttributesFactory
     */
    public function __construct(string $content, ExtensionAttributesFactory $extensionAttributesFactory)
    {
        $this->content = $content;
        $this->extensionAttributesFactory = $extensionAttributesFactory;
    }

    /**
     * Get block content field
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Deity\CmsApi\Api\Data\BlockExtensionInterface
     */
    public function getExtensionAttributes()
    {
        if ($this->extensionAttributes === null) {
            $this->extensionAttributes = $this->extensionAttributesFactory->create(BlockInterface::class);
        }
        return $this->extensionAttributes;
    }

    /**
     * Set an extension attributes object.
     *
     * @param \Deity\CmsApi\Api\Data\BlockExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(BlockExtensionInterface $extensionAttributes)
    {
        $this->extensionAttributes = $extensionAttributes;
        return $this;
    }
}
