<?php
require 'guest_quote_with_address.php';

$quoteRepository = \Magento\Framework\App\ObjectManager::getInstance()->get(
    \Magento\Quote\Api\CartRepositoryInterface::class
);
$quoteRepository->save($quote);

/** @var \Magento\Quote\Model\QuoteIdMask $quoteIdMask */
$quoteIdMask = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
    ->create(\Magento\Quote\Model\QuoteIdMaskFactory::class)
    ->create();
$quoteIdMask->setQuoteId($quote->getId());
$quoteIdMask->setDataChanges(true);
$quoteIdMask->save();
