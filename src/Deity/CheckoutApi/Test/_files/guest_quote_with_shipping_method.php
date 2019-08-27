<?php

require __DIR__ . '/../../../QuoteApi/Test/_files/guest_quote_with_address_saved.php';

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$rate = $objectManager->get(\Magento\Quote\Model\Quote\Address\Rate::class);

$quote->load('test_order_1', 'reserved_order_id');
$shippingAddress = $quote->getShippingAddress();
$shippingAddress->setShippingMethod('flatrate_flatrate')
    ->setShippingDescription('Flat Rate - Fixed')
    ->save();

$rate->setPrice(5)
    ->setCode('flatrate_flatrate')
    ->setCarrier('flatrate')
    ->setMethod('flatrate')
    ->setMethodTitle('FIXED')
    ->setCarrierTitle('Flat Rate')
    ->setAddressId($shippingAddress->getId())
    ->save();

$shippingAddress->setBaseShippingAmount($rate->getPrice());
$shippingAddress->setShippingAmount($rate->getPrice());
$shippingAddress->save();
