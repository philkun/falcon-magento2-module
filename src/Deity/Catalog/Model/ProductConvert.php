<?php
declare(strict_types=1);

namespace Deity\Catalog\Model;

use Deity\CatalogApi\Api\Data\ProductInterface as DeityProductInterface;
use Deity\CatalogApi\Api\Data\ProductInterfaceFactory;
use Deity\CatalogApi\Api\ProductConvertInterface;
use Deity\CatalogApi\Api\ProductImageProviderInterface;
use Deity\CatalogApi\Api\ProductPriceProviderInterface;
use Deity\CatalogApi\Model\ProductMapperInterface;
use Deity\CatalogApi\Model\ProductUrlPathProviderInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Profiler;
use Magento\Framework\Registry;

/**
 * Class ProductConvert
 *
 * @package Deity\Catalog\Model
 */
class ProductConvert implements ProductConvertInterface
{

    /**
     * @var ProductInterfaceFactory
     */
    private $productFactory;

    /**
     * @var ProductUrlPathProviderInterface
     */
    private $urlPathProvider;

    /**
     * @var ProductImageProviderInterface
     */
    private $imageProvider;

    /**
     * @var ProductPriceProviderInterface
     */
    private $priceProvider;

    /**
     * @var ProductMapperInterface
     */
    private $productMapper;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * ProductConvert constructor.
     * @param ProductInterfaceFactory $productFactory
     * @param ProductUrlPathProviderInterface $urlPathProvider
     * @param ProductPriceProviderInterface $priceProvider
     * @param ProductImageProviderInterface $imageProvider
     * @param Registry $registry
     * @param ProductMapperInterface $productMapper
     */
    public function __construct(
        ProductInterfaceFactory $productFactory,
        ProductUrlPathProviderInterface $urlPathProvider,
        ProductPriceProviderInterface $priceProvider,
        ProductImageProviderInterface $imageProvider,
        Registry $registry,
        ProductMapperInterface $productMapper
    ) {
        $this->registry = $registry;
        $this->productMapper = $productMapper;
        $this->urlPathProvider = $urlPathProvider;
        $this->priceProvider = $priceProvider;
        $this->imageProvider = $imageProvider;
        $this->productFactory = $productFactory;
    }

    /**
     * @inheritdoc
     */
    public function convert(Product $product): DeityProductInterface
    {
        Profiler::start('__PRODUCT_LISTING_CONVERT__', ['group' => 'Deity']);

        $categoryObject = $this->registry->registry('current_category');
        $categoryId = '';
        if ($categoryObject) {
            $categoryId = $categoryObject->getId();
        }

        $deityProduct = $this->productFactory->create();
        $deityProduct->setId($product->getId());
        $deityProduct->setName((string)$product->getName());
        $deityProduct->setTypeId((string)$product->getTypeId());
        $deityProduct->setSku((string)$product->getSku());
        $deityProduct->setUrlPath(
            $this->urlPathProvider->getProductUrlPath($product, (string)$categoryId)
        );
        $deityProduct->setIsSalable((int)$product->getIsSalable());

        $deityProduct->setImage(
            $this->imageProvider->getProductImageTypeUrl($product, 'deity_category_page_list')
        );

        Profiler::start('__PRODUCT_LISTING_CONVERT_PRICE_CALC__', ['group' => 'Deity']);
        $deityProduct->setPrice(
            $this->priceProvider->getPriceData($product)
        );
        Profiler::stop('__PRODUCT_LISTING_CONVERT_PRICE_CALC__');

        $deityProduct->setCustomAttributes(
            $product->getCustomAttributes()
        );

        $this->productMapper->map($product, $deityProduct);

        Profiler::stop('__PRODUCT_LISTING_CONVERT__');
        return $deityProduct;
    }
}
