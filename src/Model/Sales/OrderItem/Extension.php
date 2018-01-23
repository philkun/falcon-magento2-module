<?php
namespace Hatimeria\Reagento\Model\Sales\OrderItem;

use Magento\Catalog\Api\Data\ProductExtensionInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Directory\Model\Currency;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Sales\Api\Data\OrderItemExtensionInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Model\Order\Item;

class Extension
{
    /** @var ExtensionAttributesFactory */
    protected $extensionAttributesFactory;

    public function __construct(
        ExtensionAttributesFactory $extensionAttributesFactory
    ) {
        $this->extensionAttributesFactory = $extensionAttributesFactory;
    }

    /**
     * Add extension attributes to order item extension
     *
     * @param Item|OrderItemInterface $item
     */
    public function addAttributes(OrderItemInterface $item)
    {
        /** @var ProductInterface $product */
        $product = $item->getProduct();
        /** @var Currency $currency */
        $currency = $item->getOrder()->getOrderCurrency();
        /** @var ProductExtensionInterface $productAttributes */
        $productAttributes = $product->getExtensionAttributes();

        $extensionAttributes = $this->getOrderItemExtensionAttribute($item);
        $extensionAttributes->setThumbnailUrl($productAttributes->getThumbnailUrl());
        $extensionAttributes->setUrlKey($product->getUrlKey());
        $extensionAttributes->setLink($product->getProductUrl());
        $extensionAttributes->setDisplayPrice($productAttributes->getCatalogDisplayPrice());
        $extensionAttributes->setRowTotalInclTax($item->getRowTotalInclTax());
        $extensionAttributes->setCurrency($currency->getCurrencySymbol() ?: $currency->getCode());

        $item->setExtensionAttributes($extensionAttributes);
    }

    /**
     * @param OrderItemInterface $item
     * @return OrderItemExtensionInterface
     */
    protected function getOrderItemExtensionAttribute(OrderItemInterface $item)
    {
        $extensionAttributes = $item->getExtensionAttributes();
        if (!$extensionAttributes) {
            $extensionAttributes = $this->extensionAttributesFactory->create(OrderItemInterface::class);
        }

        return $extensionAttributes;
    }
}