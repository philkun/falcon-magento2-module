<?php
declare(strict_types=1);

namespace Deity\Quote\Test\Integration;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\ObjectManagerInterface;
use Magento\Quote\Api\CartItemRepositoryInterface;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

/**
 * Class QuoteRepositoryTest
 *
 * @package Deity\Quote\Test\Integration
 * @magentoAppArea webapi_rest
 */
class QuoteRepositoryTest extends TestCase
{
    /**
     * @var CartManagementInterface
     */
    private $quoteManagement;

    /**
     * @var CartItemRepositoryInterface
     */
    private $quoteItemRepository;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->quoteManagement = $this->objectManager->create(CartManagementInterface::class);
        $this->quoteItemRepository = $this->objectManager->create(CartItemRepositoryInterface::class);
    }

    /**
     * @magentoDataFixture Magento/Catalog/_files/product_simple_duplicated.php
     * @throws CouldNotSaveException
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function testProductZeroPriceAfterProductRemoval()
    {
        $quoteId = $this->quoteManagement->createEmptyCart();
        /** @var CartItemInterface $cartItem */
        $cartItem = $this->objectManager->create(CartItemInterface::class);
        $cartItem->setQty(1);
        $cartItem->setSku('simple-1');
        $cartItem->setQuoteId($quoteId);
        /** @var CartItemInterface $savedCartItem */
        $savedCartItem = $item = $this->quoteItemRepository->save($cartItem);

        $this->quoteItemRepository->deleteById($quoteId, $savedCartItem->getItemId());

        /** @var CartItemInterface $savedCartItem */
        $savedCartItem = $item = $this->quoteItemRepository->save($cartItem);

        $priceValue = $savedCartItem->getPrice();

        $this->assertEquals(10, $priceValue, 'Product');
    }
}
