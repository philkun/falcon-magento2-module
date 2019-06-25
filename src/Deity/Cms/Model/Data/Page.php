<?php
declare(strict_types=1);

namespace Deity\Cms\Model\Data;

use Deity\CmsApi\Api\Data\PageInterface;
use Deity\CmsApi\Api\Data\PageExtensionInterface;
use Magento\Framework\Api\ExtensionAttributesFactory;

/**
 * Class PageData
 *
 * @package Deity\Cms\Model
 */
class Page implements PageInterface
{

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $metaTitle;

    /**
     * @var string
     */
    private $metaDescription;

    /**
     * @var string
     */
    private $metaKeywords;

    /**
     * @var PageExtensionInterface
     */
    private $extensionAttributes;

    /**
     * @var ExtensionAttributesFactory
     */
    private $extensionAttributesFactory;

    /**
     * PageData constructor.
     * @param string $content
     * @param string $metaTitle
     * @param string $metaDescription
     * @param string $metaKeywords
     * @param ExtensionAttributesFactory $extensionAttributesFactory
     */
    public function __construct(
        string $content,
        string $metaTitle,
        string $metaDescription,
        string $metaKeywords,
        ExtensionAttributesFactory $extensionAttributesFactory
    ) {
        $this->extensionAttributesFactory = $extensionAttributesFactory;
        $this->content = $content;
        $this->metaTitle = $metaTitle;
        $this->metaDescription = $metaDescription;
        $this->metaKeywords = $metaKeywords;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Get meta title
     *
     * @return string
     */
    public function getMetaTitle(): string
    {
        return $this->metaTitle;
    }

    /**
     * Get meta description
     *
     * @return string
     */
    public function getMetaDescription(): string
    {
        return $this->metaDescription;
    }

    /**
     * Get meta keywords
     *
     * @return string
     */
    public function getMetaKeywords(): string
    {
        return $this->metaKeywords;
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Deity\CmsApi\Api\Data\PageExtensionInterface
     */
    public function getExtensionAttributes()
    {
        if ($this->extensionAttributes === null) {
            $this->extensionAttributes = $this->extensionAttributesFactory->create(PageInterface::class);
        }
        return $this->extensionAttributes;
    }

    /**
     * Set an extension attributes object.
     *
     * @param \Deity\CmsApi\Api\Data\PageExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(PageExtensionInterface $extensionAttributes)
    {
        $this->extensionAttributes = $extensionAttributes;
        return $this;
    }
}
