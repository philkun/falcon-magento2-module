<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Data;

use Deity\CatalogApi\Api\Data\ProductExtensionInterface;
use Deity\CatalogApi\Api\Data\ProductInterface;
use Deity\CatalogApi\Api\Data\ProductPriceInterface;
use Deity\CatalogApi\Model\FilterProductCustomAttributeInterface;
use Magento\Eav\Model\Config;
use Magento\Framework\Api\AttributeValue;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

/**
 * Class Product
 *
 * @package Deity\Catalog\Model\Data
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Product extends AbstractExtensibleModel implements ProductInterface
{

    /**
     * @var ProductPriceInterface
     */
    private $priceObject;

    /**
     * @var Config
     */
    private $eavConfig;

    /**
     * @var FilterProductCustomAttributeInterface
     */
    private $filterCustomAttribute;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory $customAttributeFactory
     * @param Config $eavConfig
     * @param FilterProductCustomAttributeInterface $filterCustomAttribute
     * @param AbstractResource $resource
     * @param AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        Config $eavConfig,
        FilterProductCustomAttributeInterface $filterCustomAttribute,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->eavConfig = $eavConfig;
        $this->filterCustomAttribute = $filterCustomAttribute;
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
     * @inheritdoc
     */
    public function getSku(): string
    {
        return (string)$this->getData(self::SKU);
    }

    /**
     * @inheritdoc
     */
    public function setSku(string $sku): ProductInterface
    {
        $this->setData(self::SKU, $sku);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return (string)$this->getData(self::NAME);
    }

    /**
     * @inheritdoc
     */
    public function setName(string $name): ProductInterface
    {
        $this->setData(self::NAME, $name);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getImage(): string
    {
        return (string)$this->getData(self::IMAGE);
    }

    /**
     * @inheritdoc
     */
    public function setImage(string $image): ProductInterface
    {
        $this->setData(self::IMAGE, $image);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getUrlPath(): string
    {
        return (string)$this->getData(self::URL_PATH);
    }

    /**
     * @inheritdoc
     */
    public function setUrlPath(string $urlPath): ProductInterface
    {
        $this->setData(self::URL_PATH, $urlPath);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getExtensionAttributes()
    {
        $extensionAttributes = $this->_getExtensionAttributes();
        if (!$extensionAttributes) {
            $extensionAttributes = $this->extensionAttributesFactory->create(ProductInterface::class);
            $this->_setExtensionAttributes($extensionAttributes);
            return $extensionAttributes;
        }
        return $extensionAttributes;
    }

    /**
     * @inheritdoc
     */
    public function setExtensionAttributes(
        ProductExtensionInterface $extensionAttributes
    ): ProductInterface {
        $this->_setExtensionAttributes($extensionAttributes);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getIsSalable(): int
    {
        return (int)$this->getData(self::IS_SALABLE);
    }

    /**
     * @inheritdoc
     */
    public function setIsSalable(int $salableFlag): ProductInterface
    {
        $this->setData(self::IS_SALABLE, $salableFlag);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPrice(): ProductPriceInterface
    {
        return $this->priceObject;
    }

    /**
     * @inheritdoc
     */
    public function setPrice(ProductPriceInterface $productPrice): ProductInterface
    {
        $this->priceObject = $productPrice;
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
     * @inheritdoc
     */
    public function setCustomAttributes(array $attributes)
    {
        $customAttributesCodes = $this->getCustomAttributesCodes();

        /** @var AttributeValue $attributeObject */
        foreach ($attributes as $attributeObject) {
            /* If key corresponds to custom attribute code, populate custom attributes */
            if (in_array($attributeObject->getAttributeCode(), $customAttributesCodes)) {
                $this->_data[self::CUSTOM_ATTRIBUTES][$attributeObject->getAttributeCode()] = $attributeObject;
            }
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getTypeId(): string
    {
        return (string)$this->getData(self::TYPE_ID);
    }

    /**
     * @inheritdoc
     */
    public function setTypeId(string $typeId): ProductInterface
    {
        $this->setData(self::TYPE_ID, $typeId);
        return $this;
    }
}
