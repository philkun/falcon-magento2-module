<?php
declare(strict_types=1);

namespace Deity\CmsApi\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface BlockInterface
 *
 * @package Deity\CmsApi\Api\Data
 */
interface BlockInterface extends ExtensibleDataInterface
{
    const CONTENT = 'content';

    /**
     * Get block content field
     *
     * @return string
     */
    public function getContent(): string;

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Deity\CmsApi\Api\Data\BlockExtensionInterface
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Deity\CmsApi\Api\Data\BlockExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        BlockExtensionInterface $extensionAttributes
    );
}
