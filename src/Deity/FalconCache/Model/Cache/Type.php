<?php
declare(strict_types=1);

namespace Deity\FalconCache\Model\Cache;

use Deity\FalconCacheApi\Model\CacheManagementInterface;
use Magento\Framework\App\Cache\Type\FrontendPool;
use Magento\Framework\Cache\Frontend\Decorator\TagScope;
use Magento\Framework\Cache\FrontendInterface;

/**
 * Class Type
 *
 * @package Deity\FalconCache\Model\Cache
 */
class Type extends TagScope
{
    const TYPE_IDENTIFIER = 'falcon';
    const CACHE_TAG = 'falcon';

    /**
     * @var FrontendPool
     */
    private $cacheFrontendPool;

    /**
     * @var CacheManagementInterface
     */
    private $cacheManagement;

    /**
     * @param FrontendPool $cacheFrontendPool
     * @param CacheManagementInterface $cacheManagement
     */
    public function __construct(
        FrontendPool $cacheFrontendPool,
        CacheManagementInterface $cacheManagement
    ) {
        $this->cacheManagement = $cacheManagement;
        $this->cacheFrontendPool = $cacheFrontendPool;
    }

    /**
     * Retrieve cache frontend instance being decorated
     *
     * @return FrontendInterface
     */
    protected function _getFrontend()
    {
        $frontend = parent::_getFrontend();
        if (!$frontend) {
            $frontend = $this->cacheFrontendPool->get(self::TYPE_IDENTIFIER);
            $this->setFrontend($frontend);
        }
        return $frontend;
    }

    /**
     * Retrieve cache tag name
     *
     * @return string
     */
    public function getTag()
    {
        return self::CACHE_TAG;
    }

    /**
     * Save cache record
     *
     * @param string $data
     * @param string $identifier
     * @param array $tags
     * @param int|bool|null $lifeTime
     * @return bool
     */
    public function save($data, $identifier, array $tags = [], $lifeTime = null)
    {
        //Magento do not write to Falcon Cache
        return true;
    }

    /**
     * Load cache record by its unique identifier
     *
     * @param string $identifier
     * @return string|bool
     * @api
     */
    public function load($identifier)
    {
        //Falcon cached is only used on Falcon side, not magento, return false as if it is not enabled
        return false;
    }

    /**
     * Test if a cache is available for the given id
     *
     * @param string $identifier Cache id
     * @return int|bool Last modified time of cache entry if it is available, false otherwise
     */
    public function test($identifier)
    {
        //Falcon Cache is not managed by Magento, return false as if it is not enabled
        return false;
    }

    /**
     * Remove cache record by its unique identifier
     *
     * @param string $identifier
     * @return bool
     */
    public function remove($identifier)
    {
        //something to look into
        return true;
    }

    /**
     * Clean cache records matching specified tags
     *
     * @param string $mode
     * @param array $tags
     * @return bool
     */
    public function clean($mode = \Zend_Cache::CLEANING_MODE_ALL, array $tags = [])
    {
        if ($mode === \Zend_Cache::CLEANING_MODE_MATCHING_TAG) {
            return $this->cacheManagement->cleanFalconCacheForTags($tags);
        }

        return $this->cacheManagement->cleanFalconCache();
    }
}
