<?php
declare(strict_types=1);

namespace Deity\Sales\Model\Order\Item;

use Deity\CatalogApi\Api\ProductImageProviderInterface;
use Magento\Catalog\Api\Data\ProductExtensionInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Directory\Model\Currency;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Magento\Sales\Api\Data\OrderItemExtensionInterface;
use Magento\Sales\Api\Data\OrderItemInterface;

/**
 * Class ReadHandler
 *
 * @package Deity\Sales\Model\Order\Item
 */
class ReadHandler implements ExtensionInterface
{
    /**
     * @var ExtensionAttributesFactory
     */
    private $extensionAttributesFactory;

    /**
     * @var ProductImageProviderInterface
     */
    private $productImageProvider;

    /**
     * ReadHandler constructor.
     *
     * @param ExtensionAttributesFactory $extensionAttributesFactory
     * @param ProductImageProviderInterface $productImageProvider
     */
    public function __construct(
        ExtensionAttributesFactory $extensionAttributesFactory,
        ProductImageProviderInterface $productImageProvider
    ) {
        $this->productImageProvider = $productImageProvider;
        $this->extensionAttributesFactory = $extensionAttributesFactory;
    }

    /**
     * Get order item extension attribute
     *
     * @param OrderItemInterface $item
     * @return OrderItemExtensionInterface
     */
    private function getOrderItemExtensionAttribute(OrderItemInterface $item)
    {
        $extensionAttributes = $item->getExtensionAttributes();
        if (!$extensionAttributes) {
            $extensionAttributes = $this->extensionAttributesFactory->create(OrderItemInterface::class);
        }

        return $extensionAttributes;
    }

    /**
     * Perform action on relation/extension attribute
     *
     * @param OrderItemInterface $entity
     * @param array $arguments
     * @return object|bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($entity, $arguments = [])
    {
        /** @var ProductInterface $product */
        $product = $entity->getProduct();
        /** @var Currency $currency */
        $currency = $entity->getOrder()->getOrderCurrency();

        /** @var array|null $options */
        $options = $entity->getProductOptionByCode('attributes_info');

        $thumbnailUrl = (string)$this->productImageProvider->getProductImageTypeUrl($product, 'product_list_thumbnail');

        $extensionAttributes = $this->getOrderItemExtensionAttribute($entity);
        $extensionAttributes->setThumbnailUrl($thumbnailUrl);
        $extensionAttributes->setUrlKey($product->getUrlKey());
        $extensionAttributes->setLink($product->getProductUrl());
        $extensionAttributes->setRowTotalInclTax($entity->getRowTotalInclTax());
        $extensionAttributes->setCurrency($currency->getCurrencySymbol() ?: $currency->getCode());
        $extensionAttributes->setOptions($options ? json_encode($options) : null);

        $entity->setExtensionAttributes($extensionAttributes);
    }
}
