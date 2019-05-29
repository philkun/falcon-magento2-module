<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api\Data;

use Magento\Framework\Api\CustomAttributesDataInterface;

/**
 * Interface ProductDetailInterface
 *
 * @package Deity\CatalogApi\Api\Data
 */
interface ProductDetailInterface extends CustomAttributesDataInterface
{
    const ID_FIELD_KEY = 'id';
    const SKU_FIELD_KEY = 'sku';
    const NAME_FIELD_KEY = 'name';
    const DESCRIPTION_FIELD_KEY = 'description';
    const URL_PATH_FIELD_KEY = 'url_path';
    const IMAGE_FIELD_KEY = 'image';
    const IMAGE_RESIZED_FIELD_KEY = 'image_resized';
    const TYPE_ID_FIELD_KEY = 'type_id';
    const IS_SALABLE_FIELD_KEY = 'is_salable';
    const MEDIA_GALLERY_FIELD_KEY = 'media_gallery_sizes';
    const PRICE_FIELD_KEY = 'price';
    const TIER_PRICES_FIELD_KEY = 'tier_prices';
    const STOCK_FIELD_KEY = 'stock';
    const PRODUCT_LINKS_FIELD_KEY = 'product_links';
    const OPTIONS_FIELD_KEY = 'options';

    /**
     * Get product id
     *
     * @return int
     */
    public function getId(): int;

    /**
     * Set product id
     *
     * @param int $id
     * @return ProductDetailInterface
     */
    public function setId($id);

    /**
     * Get product description
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Set product description
     *
     * @param string $description
     * @return ProductDetailInterface
     */
    public function setDescription(string $description): ProductDetailInterface;

    /**
     * Get product price object
     *
     * @return \Deity\CatalogApi\Api\Data\ProductPriceInterface
     */
    public function getPrice(): ProductPriceInterface;

    /**
     * Set product price
     *
     * @param ProductPriceInterface $price
     * @return ProductDetailInterface
     */
    public function setPrice(ProductPriceInterface $price): ProductDetailInterface;

    /**
     * Get product sku
     *
     * @return string
     */
    public function getSku(): string;

    /**
     * Set product sku
     *
     * @param string $sku
     * @return ProductDetailInterface
     */
    public function setSku(string $sku): ProductDetailInterface;

    /**
     * Get product name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set product name
     *
     * @param string $name
     * @return ProductDetailInterface
     */
    public function setName(string $name): ProductDetailInterface;

    /**
     * Set product image
     *
     * @param string $image
     * @return ProductDetailInterface
     */
    public function setImage(string $image): ProductDetailInterface;

    /**
     * Get full size product image url
     *
     * @return string
     */
    public function getImage(): string;

    /**
     * Get resized product image
     *
     * @return string
     */
    public function getImageResized(): string;

    /**
     * Set image resized
     *
     * @param string $resizedImage
     * @return ProductDetailInterface
     */
    public function setImageResized(string $resizedImage): ProductDetailInterface;

    /**
     * Get product type id
     *
     * @return string
     */
    public function getTypeId(): string;

    /**
     * Set type id
     *
     * @param string $typeId
     * @return ProductDetailInterface
     */
    public function setTypeId(string $typeId): ProductDetailInterface;

    /**
     * Get if product is salable
     *
     * @return int
     */
    public function getIsSalable(): int;

    /**
     * Set product is salable flag
     *
     * @param int $isSalable
     * @return ProductDetailInterface
     */
    public function setIsSalable(int $isSalable): ProductDetailInterface;

    /**
     * Get media AutocompleteItemExtensionInterfacegallery items
     *
     * @return \Deity\CatalogApi\Api\Data\GalleryMediaEntrySizeInterface[]
     */
    public function getMediaGallerySizes(): array;

    /**
     * Set media gallery
     *
     * @param \Deity\CatalogApi\Api\Data\GalleryMediaEntrySizeInterface[] $mediaGallerySizes
     * @return ProductDetailInterface
     */
    public function setMediaGallerySizes(array $mediaGallerySizes): ProductDetailInterface;
    /**
     * Get product url path
     *
     * @return string
     */
    public function getUrlPath(): string;

    /**
     * Set url path
     *
     * @param string $urlPath
     * @return ProductDetailInterface
     */
    public function setUrlPath(string $urlPath): ProductDetailInterface;

    /**
     * Gets list of product tier prices
     *
     * @return \Magento\Catalog\Api\Data\ProductTierPriceInterface[]|null
     */
    public function getTierPrices();

    /**
     * Set product tier prices
     *
     * @param \Magento\Catalog\Api\Data\ProductTierPriceInterface[] $tierPrices
     * @return ProductDetailInterface
     */
    public function setTierPrices(array $tierPrices): ProductDetailInterface;

    /**
     * Get stock info
     *
     * @return \Deity\CatalogApi\Api\Data\ProductStockInterface
     */
    public function getStock(): ProductStockInterface;

    /**
     * Set product stock
     *
     * @param ProductStockInterface $stock
     * @return ProductDetailInterface
     */
    public function setStock(ProductStockInterface $stock): ProductDetailInterface;

    /**
     * Get product links
     *
     * @return \Magento\Catalog\Api\Data\ProductLinkInterface[]
     */
    public function getProductLinks(): array;

    /**
     * Set product links
     *
     * @param \Magento\Catalog\Api\Data\ProductLinkInterface[] $links
     * @return ProductDetailInterface
     */
    public function setProductLinks(array $links): ProductDetailInterface;

    /**
     * Get product options
     *
     * @return \Magento\Catalog\Api\Data\ProductCustomOptionInterface[]|null
     */
    public function getOptions(): array;

    /**
     * Set product options
     *
     * @param \Magento\Catalog\Api\Data\ProductCustomOptionInterface[] $options
     * @return ProductDetailInterface
     */
    public function setOptions(array $options): ProductDetailInterface;

    /**
     * Get extension attributes
     *
     * @return \Deity\CatalogApi\Api\Data\ProductDetailExtensionInterface
     */
    public function getExtensionAttributes();

    /**
     * Set extension attributes
     *
     * @param \Deity\CatalogApi\Api\Data\ProductDetailExtensionInterface $extensionAttributes
     * @return \Deity\CatalogApi\Api\Data\ProductDetailInterface
     */
    public function setExtensionAttributes(
        ProductDetailExtensionInterface $extensionAttributes
    ): ProductDetailInterface;
}
