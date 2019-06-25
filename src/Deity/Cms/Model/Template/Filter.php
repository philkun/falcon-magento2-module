<?php
declare(strict_types=1);

namespace Deity\Cms\Model\Template;

use Deity\Base\Model\FalconConfigProvider;
use Magento\Email\Model\Template\Filter as MagentoFilter;

/**
 * Class Filter
 *
 * @package Deity\Cms\Model\Template
 */
class Filter extends MagentoFilter
{

    /**
     * Replace Magento url with falcon frontend url
     *
     * @param string[] $construction
     * @return mixed|string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function storeDirective($construction)
    {
        $magentoUrl = parent::storeDirective($construction);

        $magentoStoreUrl = $this->_storeManager->getStore()->getBaseUrl();
        $falconFrontendUrl = $this->_scopeConfig->getValue(FalconConfigProvider::DEITY_FALCON_FRONTEND_URL);
        if (!$falconFrontendUrl) {
            return $magentoUrl;
        }
        return str_replace($magentoStoreUrl, $falconFrontendUrl, $magentoUrl);
    }

    /**
     * Omit view construction
     *
     * @param string[] $construction
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function viewDirective($construction)
    {
        return '';
    }

    /**
     * Omit layout construction
     *
     * @param string[] $construction
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function layoutDirective($construction)
    {
        return '';
    }

    /**
     * Omit block construction
     *
     * @param string[] $construction
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function blockDirective($construction)
    {
        return '';
    }

    /**
     * Omit css directive
     *
     * @param string[] $construction
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function cssDirective($construction)
    {
        return '';
    }

    /**
     * Omit widget directives
     *
     * @param string[] $constructions
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function widgetDirective($constructions)
    {
        return '';
    }

    /**
     * Omit template directives
     *
     * @param string[] $constructions
     * @return mixed|string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function templateDirective($constructions)
    {
        return '';
    }
}
