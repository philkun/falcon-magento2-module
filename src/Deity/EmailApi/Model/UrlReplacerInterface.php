<?php
declare(strict_types=1);

namespace Deity\EmailApi\Model;

/**
 * Interface UrlReplacerApi
 *
 * @package Deity\EmailApi\Model
 */
interface UrlReplacerInterface
{
    /**
     * Replace magento domain for falcon domain in given URL
     *
     * @param string $url
     * @return string
     */
    public function replaceLinkToFalcon(string $url): string;
}
