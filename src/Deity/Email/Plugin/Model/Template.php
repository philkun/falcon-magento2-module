<?php
declare(strict_types=1);

namespace Deity\Email\Plugin\Model;

use Deity\EmailApi\Model\UrlReplacerInterface;

/**
 * Class Template
 *
 * @package Deity\Email\Plugin\Email
 */
class Template
{
    /**
     * @var UrlReplacerInterface
     */
    private $urlReplacer;

    /**
     * Template constructor.
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
     * @param \Magento\Email\Model\Template $subject
     * @param string $result
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetUrl(\Magento\Email\Model\Template $subject, $result)
    {
        return $this->urlReplacer->replaceLinkToFalcon($result);
    }
}
