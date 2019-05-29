<?php

use Magento\Catalog\Api\Data\ProductTierPriceExtensionFactory;
use Magento\Catalog\Api\Data\ProductExtensionInterfaceFactory;

\Magento\TestFramework\Helper\Bootstrap::getInstance()->reinitialize();

/** @var \Magento\TestFramework\ObjectManager $objectManager */
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var \Magento\Catalog\Api\CategoryLinkManagementInterface $categoryLinkManagement */
$categoryLinkManagement = $objectManager->get(\Magento\Catalog\Api\CategoryLinkManagementInterface::class);

$tierPrices = [];
/** @var \Magento\Catalog\Api\Data\ProductTierPriceInterfaceFactory $tierPriceFactory */
$tierPriceFactory = $objectManager->get(\Magento\Catalog\Api\Data\ProductTierPriceInterfaceFactory::class);
/** @var  $tpExtensionAttributes */
$tpExtensionAttributesFactory = $objectManager->get(ProductTierPriceExtensionFactory::class);
/** @var  $productExtensionAttributes */
$productExtensionAttributesFactory = $objectManager->get(ProductExtensionInterfaceFactory::class);

$adminWebsite = $objectManager->get(\Magento\Store\Api\WebsiteRepositoryInterface::class)->get('admin');
$tierPriceExtensionAttributes1 = $tpExtensionAttributesFactory->create()
    ->setWebsiteId($adminWebsite->getId());
$productExtensionAttributesWebsiteIds = $productExtensionAttributesFactory->create(
    ['website_ids' => $adminWebsite->getId()]
);

$tierPrices[] = $tierPriceFactory->create(
    [
        'data' => [
            'customer_group_id' => \Magento\Customer\Model\Group::CUST_GROUP_ALL,
            'qty' => 2,
            'value' => 8
        ]
    ]
)->setExtensionAttributes($tierPriceExtensionAttributes1);

$tierPrices[] = $tierPriceFactory->create(
    [
        'data' => [
            'customer_group_id' => \Magento\Customer\Model\Group::CUST_GROUP_ALL,
            'qty' => 5,
            'value' => 5
        ]
    ]
)->setExtensionAttributes($tierPriceExtensionAttributes1);

$tierPrices[] = $tierPriceFactory->create(
    [
        'data' => [
            'customer_group_id' => \Magento\Customer\Model\Group::NOT_LOGGED_IN_ID,
            'qty' => 3,
            'value' => 5
        ]
    ]
)->setExtensionAttributes($tierPriceExtensionAttributes1);

$tierPrices[] = $tierPriceFactory->create(
    [
        'data' => [
            'customer_group_id' => \Magento\Customer\Model\Group::NOT_LOGGED_IN_ID,
            'qty' => 3.2,
            'value' => 6,
        ]
    ]
)->setExtensionAttributes($tierPriceExtensionAttributes1);

$tierPriceExtensionAttributes2 = $tpExtensionAttributesFactory->create()
    ->setWebsiteId($adminWebsite->getId())
    ->setPercentageValue(50);

$tierPrices[] = $tierPriceFactory->create(
    [
        'data' => [
            'customer_group_id' => \Magento\Customer\Model\Group::NOT_LOGGED_IN_ID,
            'qty' => 10
        ]
    ]
)->setExtensionAttributes($tierPriceExtensionAttributes2);

/** @var $product \Magento\Catalog\Model\Product */
$product = $objectManager->create(\Magento\Catalog\Model\Product::class);
$product->isObjectNew(true);
$product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
    ->setId(1)
    ->setAttributeSetId(4)
    ->setWebsiteIds([1])
    ->setName('Simple Product')
    ->setSku('simple')
    ->setPrice(10)
    ->setSpecialPrice(9.99)
    ->setWeight(1)
    ->setShortDescription("Short description")
    ->setTaxClassId(0)
    ->setTierPrices($tierPrices)
    ->setDescription('Description with <b>html tag</b>')
    ->setExtensionAttributes($productExtensionAttributesWebsiteIds)
    ->setMetaTitle('meta title')
    ->setMetaKeyword('meta keyword')
    ->setMetaDescription('meta description')
    ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
    ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
    ->setStockData(
        [
            'use_config_manage_stock'   => 1,
            'qty'                       => 100,
            'is_qty_decimal'            => 0,
            'is_in_stock'               => 1,
        ]
    );

/** @var \Magento\Catalog\Api\ProductRepositoryInterface $productRepository */
$productRepository = $objectManager->create(\Magento\Catalog\Api\ProductRepositoryInterface::class);
$productRepository->save($product);

$categoryLinkManagement->assignProductToCategories(
    $product->getSku(),
    [2]
);
