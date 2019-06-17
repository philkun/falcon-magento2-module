<?php
declare(strict_types=1);

namespace Deity\FalconCache\Plugin;

use Closure;
use Deity\FalconCacheApi\Model\CacheManagementInterface;
use Magento\Framework\App\Cache\StateInterface;
use Magento\Framework\App\Cache\Tag\Resolver;
use Magento\Framework\App\Cache\Type\FrontendPool;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Zend_Cache;

/**
 * Class FlushCacheByTags
 *
 * @package Deity\FalconCache\Plugin
 */
class FlushCacheByTags
{
    /**
     * @var Resolver
     */
    private $tagResolver;

    /**
     * @var CacheManagementInterface
     */
    private $cacheManagement;

    /**
     * FlushCacheByTags constructor.
     *
     * @param CacheManagementInterface $cacheManagement
     * @param Resolver $tagResolver
     */
    public function __construct(
        CacheManagementInterface $cacheManagement,
        Resolver $tagResolver
    ) {
        $this->cacheManagement = $cacheManagement;
        $this->tagResolver = $tagResolver;
    }

    /**
     * Clean cache on save object
     *
     * @param AbstractResource $subject
     * @param Closure $proceed
     * @param AbstractModel $object
     * @return AbstractResource
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundSave(
        AbstractResource $subject,
        Closure $proceed,
        AbstractModel $object
    ) {
        $result = $proceed($object);
        $tags = $this->tagResolver->getTags($object);
        $this->cleanCacheByTags($tags);

        return $result;
    }

    /**
     * Clean cache on delete object
     *
     * @param AbstractResource $subject
     * @param Closure $proceed
     * @param AbstractModel $object
     * @return AbstractResource
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundDelete(
        AbstractResource $subject,
        Closure $proceed,
        AbstractModel $object
    ) {
        $tags = $this->tagResolver->getTags($object);
        $result = $proceed($object);
        $this->cleanCacheByTags($tags);
        return $result;
    }

    /**
     * Clean cache by tags
     *
     * @param  string[] $tags
     * @return void
     */
    private function cleanCacheByTags($tags)
    {
        if (empty($tags)) {
            return;
        }

        $this->cacheManagement->cleanFalconCacheForTags($tags);
    }
}
