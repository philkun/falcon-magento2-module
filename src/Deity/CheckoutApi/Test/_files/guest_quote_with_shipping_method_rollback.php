<?php

/** @var $objectManager \Magento\TestFramework\ObjectManager */

use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\TestFramework\Helper\Bootstrap;

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$quote = $objectManager->create(\Magento\Quote\Model\Quote::class);
$quote->load('test_order_1', 'reserved_order_id')->delete();

/** @var \Magento\Quote\Model\QuoteIdMask $quoteIdMask */
$quoteIdMask = $objectManager->create(\Magento\Quote\Model\QuoteIdMask::class);
$quoteIdMask->delete($quote->getId());

/** @var \Magento\Framework\Registry $registry */
$registry = Bootstrap::getObjectManager()->get(\Magento\Framework\Registry::class);
$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

/** @var Magento\Sales\Model\OrderRepository $orderRepository */
$orderRepository = $objectManager->create(\Magento\Sales\Model\OrderRepository::class);
/** @var SearchCriteria $searchCriteria */
$searchCriteria = $objectManager->get(SearchCriteriaBuilder::class)
    ->create();
$orderCollection = $orderRepository->getList($searchCriteria)->getItems();
foreach ($orderCollection as $orderItem) {
    $orderRepository->delete($orderItem);
}

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', false);
require __DIR__ . '/../../../QuoteApi/Test/_files/guest_quote_with_address_rollback.php';
