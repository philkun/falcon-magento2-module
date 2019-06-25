<?php
declare(strict_types=1);

namespace Deity\CmsApi\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface PageDataInterface
 *
 * @package Deity\CmsApi\Api\Data
 */
interface PageInterface extends ExtensibleDataInterface
{

    const CONTENT = 'content';

    const META_TITLE = 'meta_title';

    const META_DESCRIPTION = 'meta_description';

    const META_KEYWORDS = 'meta_keywords';

    /**
     * Get content
     *
     * @return string
     */
    public function getContent(): string;

    /**
     * Get meta title
     *
     * @return string
     */
    public function getMetaTitle(): string;

    /**
     * Get meta description
     *
     * @return string
     */
    public function getMetaDescription(): string;

    /**
     * Get meta keywords
     *
     * @return string
     */
    public function getMetaKeywords(): string;

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Deity\CmsApi\Api\Data\PageExtensionInterface
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Deity\CmsApi\Api\Data\PageExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        PageExtensionInterface $extensionAttributes
    );
}
