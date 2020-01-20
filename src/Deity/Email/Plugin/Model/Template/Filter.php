<?php
declare(strict_types=1);

namespace Deity\Email\Plugin\Model\Template;

use Deity\EmailApi\Model\UrlReplacerInterface;

/**
 * Class Filter
 *
 * @package Deity\Email\Plugin\Model\Template
 */
class Filter
{
    /**
     * @var UrlReplacerInterface
     */
    private $urlReplacer;

    /**
     * Filter constructor.
     *
     * @param UrlReplacerInterface $urlReplacer
     */
    public function __construct(UrlReplacerInterface $urlReplacer)
    {
        $this->urlReplacer = $urlReplacer;
    }

    /**
     * After plugin
     *
     * @param \Magento\Email\Model\Template\Filter $subject
     * @param string $result
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterStoreDirective(\Magento\Email\Model\Template\Filter $subject, $result)
    {
        return $this->urlReplacer->replaceLinkToFalcon($result);
    }
}
