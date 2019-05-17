<?php
declare(strict_types=1);

namespace Deity\Quote\Plugin\Model;

use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;

/**
 * Class QuoteRepository
 *
 * @package Deity\Quote\Plugin\Model
 */
class QuoteRepository
{

    /**
     * @var AddressInterfaceFactory
     */
    private $quoteAddressFactory;

    /**
     * QuoteRepository constructor.
     * @param AddressInterfaceFactory $quoteAddressFactory
     */
    public function __construct(AddressInterfaceFactory $quoteAddressFactory)
    {
        $this->quoteAddressFactory = $quoteAddressFactory;
    }

    /**
     * Make sure every Cart object has a shipping address. For correct price display
     *
     * @param CartRepositoryInterface $cartRepository
     * @param CartInterface $quote
     * @return CartInterface
     */
    public function afterGet(CartRepositoryInterface $cartRepository, CartInterface $quote)
    {
        if (!$quote->getShippingAddress()) {
            $quote->setShippingAddress($this->quoteAddressFactory->create());
            $cartRepository->save($quote);
        }

        return $quote;
    }
}
