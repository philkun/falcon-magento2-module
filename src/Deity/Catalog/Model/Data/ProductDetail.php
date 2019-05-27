<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Data;

use Deity\CatalogApi\Api\Data\ProductDetailExtensionInterface;
use Deity\CatalogApi\Api\Data\ProductDetailInterface;
use Deity\CatalogApi\Api\Data\ProductPriceInterface;
use Deity\CatalogApi\Api\Data\ProductStockInterface;
use Deity\CatalogApi\Model\FilterProductCustomAttributeInterface;
use Magento\Eav\Model\Config;
use Magento\Framework\Api\AttributeInterface;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

/**
 * Class ProductDetail
 *
 * @package Deity\Catalog\Model\Data
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ProductDetail extends AbstractExtensibleModel implements ProductDetailInterface
{
    /**
     * @var Config
     */
    private $eavConfig;

    /**
     * @var FilterProductCustomAttributeInterface
     */
    private $filterCustomAttribute;

    /**
     * ProductDetail constructor.
     * @param ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory $customAttributeFactory
     * @param FilterProductCustomAttributeInterface $filterCustomAttribute
     * @param Config $config
     * @param Context $context
     * @param Registry $registry
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        FilterProductCustomAttributeInterface $filterCustomAttribute,
        Config $config,
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->filterCustomAttribute = $filterCustomAttribute;
        $this->eavConfig = $config;
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * Get product sku
     *
     * @return string
     */
    public function getSku(): string
    {
        return (string)$this->getData(self::SKU_FIELD_KEY);
    }

    /**
     * Get product name
     *
     * @return string
     */
    public function getName(): string
    {
        return (string)$this->getData(self::NAME_FIELD_KEY);
    }

    /**
     * Get full size product image url
     *
     * @return string
     */
    public function getImage(): string
    {
        return (string)$this->getData(self::IMAGE_FIELD_KEY);
    }

    /**
     * Get resized product image
     *
     * @return string
     */
    public function getImageResized(): string
    {
        return (string)$this->getData(self::IMAGE_RESIZED_FIELD_KEY);
    }

    /**
     * Get product type id
     *
     * @return string
     */
    public function getTypeId(): string
    {
        return (string)$this->getData(self::TYPE_ID_FIELD_KEY);
    }

    /**
     * Get if product is salable
     *
     * @return int
     */
    public function getIsSalable(): int
    {
        return (int)$this->getData(self::IS_SALABLE_FIELD_KEY);
    }

    /**
     * Get product id
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->getData(self::ID_FIELD_KEY);
    }

    /**
     * Get media gallery items
     *
     * @return \Deity\CatalogApi\Api\Data\GalleryMediaEntrySizeInterface[]
     */
    public function getMediaGallerySizes(): array
    {
        return $this->getData(self::MEDIA_GALLERY_FIELD_KEY);
    }

    /**
     * Get extension attributes
     *
     * @return \Deity\CatalogApi\Api\Data\ProductDetailExtensionInterface
     */
    public function getExtensionAttributes()
    {
        if (!$this->extensionAttributes) {
            $this->extensionAttributes = $this->extensionAttributesFactory->create(ProductDetailInterface::class);
        }

        return $this->extensionAttributes;
    }

    /**
     * Set extension attributes
     *
     * @param \Deity\CatalogApi\Api\Data\ProductDetailExtensionInterface $extensionAttributes
     * @return \Deity\CatalogApi\Api\Data\ProductDetailInterface
     */
    public function setExtensionAttributes(ProductDetailExtensionInterface $extensionAttributes): ProductDetailInterface
    {
        $this->extensionAttributes = $extensionAttributes;
        return $this;
    }

    /**
     * Get product url path
     *
     * @return string
     */
    public function getUrlPath(): string
    {
        return (string)$this->getData(self::URL_PATH_FIELD_KEY);
    }

    /**
     * Get product price object
     *
     * @return \Deity\CatalogApi\Api\Data\ProductPriceInterface
     */
    public function getPrice(): ProductPriceInterface
    {
        return $this->getData(self::PRICE_FIELD_KEY);
    }

    /**
     * Gets list of product tier prices
     *
     * @return \Magento\Catalog\Api\Data\ProductTierPriceInterface[]|null
     */
    public function getTierPrices()
    {
        return $this->getData(self::TIER_PRICES_FIELD_KEY);
    }

    /**
     * Get stock info
     *
     * @return ProductStockInterface
     */
    public function getStock(): ProductStockInterface
    {
        return $this->getData(self::STOCK_FIELD_KEY);
    }

    /**
     * Get product links
     *
     * @return \Magento\Catalog\Api\Data\ProductLinkInterface[]
     */
    public function getProductLinks(): array
    {
        if (!isset($this->_data[self::PRODUCT_LINKS_FIELD_KEY])) {
            $this->_data[self::PRODUCT_LINKS_FIELD_KEY] = [];
        }
        return $this->_data[self::PRODUCT_LINKS_FIELD_KEY];
    }

    /**
     * Get product options
     *
     * @return \Magento\Catalog\Api\Data\ProductCustomOptionInterface[]|null
     */
    public function getOptions(): array
    {
        if (!isset($this->_data[self::OPTIONS_FIELD_KEY])) {
            $this->_data[self::OPTIONS_FIELD_KEY] = [];
        }
        return $this->_data[self::OPTIONS_FIELD_KEY];
    }

    /**
     * Get an attribute value.
     *
     * @param string $attributeCode
     * @return \Magento\Framework\Api\AttributeInterface|null
     */
    public function getCustomAttribute($attributeCode)
    {
        return $this->_data[self::CUSTOM_ATTRIBUTES][$attributeCode] ?? null;
    }

    /**
     * Set an attribute value for a given attribute code
     *
     * @param string $attributeCode
     * @param mixed $attributeValue
     * @return $this
     */
    public function setCustomAttribute($attributeCode, $attributeValue)
    {
        $customAttributesCodes = $this->getCustomAttributesCodes();
        /* If key corresponds to custom attribute code, populate custom attributes */
        if (in_array($attributeCode, $customAttributesCodes)) {
            /** @var AttributeInterface $attribute */
            $attribute = $this->customAttributeFactory->create();
            $attribute->setAttributeCode($attributeCode)
                ->setValue($attributeValue);
            $this->_data[self::CUSTOM_ATTRIBUTES][$attributeCode] = $attribute;
        }

        return $this;
    }

    /**
     * Retrieve custom attributes values.
     *
     * @return \Magento\Framework\Api\AttributeInterface[]
     */
    public function getCustomAttributes()
    {
        if (!isset($this->_data[self::CUSTOM_ATTRIBUTES])) {
            $this->_data[self::CUSTOM_ATTRIBUTES] = [];
        }

        return array_values($this->_data[self::CUSTOM_ATTRIBUTES]);
    }

    /**
     * Set array of custom attributes
     *
     * @param \Magento\Framework\Api\AttributeInterface[] $attributes
     * @return $this
     * @throws \LogicException
     */
    public function setCustomAttributes(array $attributes)
    {
        $customAttributesCodes = $this->getCustomAttributesCodes();
        /** @var AttributeInterface $attributeObject */
        foreach ($attributes as $attributeObject) {
            /* If key corresponds to custom attribute code, populate custom attributes */
            if (in_array($attributeObject->getAttributeCode(), $customAttributesCodes)) {
                $this->_data[self::CUSTOM_ATTRIBUTES][$attributeObject->getAttributeCode()] = $attributeObject;
            }
        }
        return $this;
    }

    /**
     * Get a list of custom attribute codes that belongs to product attribute set.
     *
     * If attribute set not specified for product will return all product attribute codes
     *
     * @return string[]
     */
    protected function getCustomAttributesCodes()
    {
        if ($this->customAttributesCodes === null) {
            $this->customAttributesCodes = array_keys(
                $this->filterCustomAttribute->execute(
                    $this->eavConfig->getEntityAttributes(
                        \Magento\Catalog\Model\Product::ENTITY,
                        $this
                    )
                )
            );
        }

        return $this->customAttributesCodes;
    }

    /**
     * Get Product description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return (string)$this->getData(self::DESCRIPTION_FIELD_KEY);
    }

    /**
     * Set product description
     *
     * @param string $description
     * @return ProductDetailInterface
     */
    public function setDescription(string $description): ProductDetailInterface
    {
        $this->setData(self::DESCRIPTION_FIELD_KEY, $description);
        return $this;
    }

    /**
     * Set product price
     *
     * @param ProductPriceInterface $price
     * @return ProductDetailInterface
     */
    public function setPrice(ProductPriceInterface $price): ProductDetailInterface
    {
        $this->setData(self::PRICE_FIELD_KEY, $price);
        return $this;
    }

    /**
     * Set product sku
     *
     * @param string $sku
     * @return ProductDetailInterface
     */
    public function setSku(string $sku): ProductDetailInterface
    {
        $this->setData(self::SKU_FIELD_KEY, $sku);
        return $this;
    }

    /**
     * Set product name
     *
     * @param string $name
     * @return ProductDetailInterface
     */
    public function setName(string $name): ProductDetailInterface
    {
        $this->setData(self::NAME_FIELD_KEY, $name);
        return $this;
    }

    /**
     * Set product image
     *
     * @param string $image
     * @return ProductDetailInterface
     */
    public function setImage(string $image): ProductDetailInterface
    {
        $this->setData(self::IMAGE_FIELD_KEY, $image);
        return $this;
    }

    /**
     * Set image resized
     *
     * @param string $resizedImage
     * @return ProductDetailInterface
     */
    public function setImageResized(string $resizedImage): ProductDetailInterface
    {
        $this->setData(self::IMAGE_RESIZED_FIELD_KEY, $resizedImage);
        return $this;
    }

    /**
     * Set type id
     *
     * @param string $typeId
     * @return ProductDetailInterface
     */
    public function setTypeId(string $typeId): ProductDetailInterface
    {
        $this->setData(self::TYPE_ID_FIELD_KEY, $typeId);
        return $this;
    }

    /**
     * Set product is salable flag
     *
     * @param int $isSalable
     * @return ProductDetailInterface
     */
    public function setIsSalable(int $isSalable): ProductDetailInterface
    {
        $this->setData(self::IS_SALABLE_FIELD_KEY, $isSalable);
        return $this;
    }

    /**
     * Set media gallery
     *
     * @param \Deity\CatalogApi\Api\Data\GalleryMediaEntrySizeInterface[] $mediaGallerySizes
     * @return ProductDetailInterface
     */
    public function setMediaGallerySizes(array $mediaGallerySizes): ProductDetailInterface
    {
        $this->setData(self::MEDIA_GALLERY_FIELD_KEY, $mediaGallerySizes);
        return $this;
    }

    /**
     * Set url path
     *
     * @param string $urlPath
     * @return ProductDetailInterface
     */
    public function setUrlPath(string $urlPath): ProductDetailInterface
    {
        $this->setData(self::URL_PATH_FIELD_KEY, $urlPath);
        return $this;
    }

    /**
     * Set product tier prices
     *
     * @param \Magento\Catalog\Api\Data\ProductTierPriceInterface[] $tierPrices
     * @return ProductDetailInterface
     */
    public function setTierPrices(array $tierPrices): ProductDetailInterface
    {
        $this->setData(self::TIER_PRICES_FIELD_KEY, $tierPrices);
        return $this;
    }

    /**
     * Set product stock
     *
     * @param ProductStockInterface $stock
     * @return ProductDetailInterface
     */
    public function setStock(ProductStockInterface $stock): ProductDetailInterface
    {
        $this->setData(self::STOCK_FIELD_KEY, $stock);
        return $this;
    }

    /**
     * Set product links
     *
     * @param \Magento\Catalog\Api\Data\ProductLinkInterface[] $links
     * @return ProductDetailInterface
     */
    public function setProductLinks(array $links): ProductDetailInterface
    {
        $this->setData(self::PRODUCT_LINKS_FIELD_KEY, $links);
        return $this;
    }

    /**
     * Set product options
     *
     * @param \Magento\Catalog\Api\Data\ProductCustomOptionInterface[] $options
     * @return ProductDetailInterface
     */
    public function setOptions(array $options): ProductDetailInterface
    {
        $this->setData(self::OPTIONS_FIELD_KEY, $options);
        return $this;
    }
}
