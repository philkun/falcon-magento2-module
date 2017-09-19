<?php

namespace Hatimeria\Reagento\Helper;

use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Model\Product as MagentoProduct;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class Image extends AbstractHelper
{
    /** @var StoreManagerInterface */
    protected $storeManager;

    /** @var ImageHelper */
    protected $imageHelper;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        ImageHelper $image
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->imageHelper = $image;
    }

    /**
     * @param MagentoProduct $product
     * @param string $imageFile
     * @param string $size
     * @return string
     */
    public function getProductImageUrl(MagentoProduct $product, $imageFile, $size)
    {
        return $this->imageHelper->init($product, $size)
            ->setImageFile($imageFile)
            ->getUrl();
    }

    /**
     * @param MagentoProduct $product
     * @param string $size
     * @return string
     */
    public function getMainProductImageUrl(MagentoProduct $product, $size)
    {
        return $product->getImage() ? $this->getProductImageUrl($product, $product->getImage(), $size) : null;
    }

    /**
     * @return string
     */
    public function getBaseMediaUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
    }
}