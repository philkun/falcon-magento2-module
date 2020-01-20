<?php
declare(strict_types=1);

namespace Deity\Email\Model;

use Deity\Base\Model\FalconConfigProvider;
use Deity\EmailApi\Model\UrlReplacerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Url;

/**
 * Class UrlReplacer
 *
 * @package Deity\Email\Model
 */
class UrlReplacer implements UrlReplacerInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Url
     */
    private $urlModel;

    /**
     * UrlDecorator constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param Url $urlModel
     */
    public function __construct(ScopeConfigInterface $scopeConfig, Url $urlModel)
    {
        $this->scopeConfig = $scopeConfig;
        $this->urlModel = $urlModel;
    }

    /**
     * Replace magento domain for falcon domain in given URL
     *
     * @param string $url
     * @return string
     */
    public function replaceLinkToFalcon(string $url): string
    {
        $domainValue =  $this->scopeConfig->getValue(FalconConfigProvider::DEITY_FALCON_FRONTEND_URL);

        if (empty($domainValue)) {
            return $url;
        }

        if (strpos($url, '/pub/') !== false || strpos($url, '/static/') !== false) {
            return $url;
        }

        $magentoDomain = $this->urlModel->getBaseUrl();
        return str_replace($magentoDomain, rtrim(trim($domainValue), '/') . "/", $url);
    }
}
