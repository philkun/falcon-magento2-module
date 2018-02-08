<?php

namespace Hatimeria\Reagento\Model\ResourceModel\Category;

use Hatimeria\Reagento\Helper\Breadcrumb;
use Hatimeria\Reagento\Helper\Category;
use Magento\Catalog\Model\Category as CategoryModel;
use Magento\Catalog\Model\ResourceModel\Category\Collection as MagentoCategoryCollection;
use Magento\Eav\Model\Config;
use Magento\Eav\Model\EntityFactory as ModelEntityFactory;
use Magento\Eav\Model\ResourceModel\Helper;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Data\Collection\EntityFactory as CollectionEntityFactory;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Validator\UniversalFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class Collection extends MagentoCategoryCollection
{
    /** @var Category */
    protected $hatimeriaCategoryHelper;

    /** @var Breadcrumb */
    protected $hatimeriaBreadcrumbHelper;

    /**
     * Collection constructor.
     * @param CollectionEntityFactory $entityFactory
     * @param LoggerInterface $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ManagerInterface $eventManager
     * @param Config $eavConfig
     * @param ResourceConnection $resource
     * @param ModelEntityFactory $eavEntityFactory
     * @param Helper $resourceHelper
     * @param UniversalFactory $universalFactory
     * @param StoreManagerInterface $storeManager
     * @param Category $hatimeriaCategoryHelper
     * @param Breadcrumb $hatimeriaBreadcrumbHelper
     * @param AdapterInterface|null $connection
     */
    public function __construct(
        CollectionEntityFactory $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        Config $eavConfig,
        ResourceConnection $resource,
        ModelEntityFactory $eavEntityFactory,
        Helper $resourceHelper,
        UniversalFactory $universalFactory,
        StoreManagerInterface $storeManager,
        Category $hatimeriaCategoryHelper,
        Breadcrumb $hatimeriaBreadcrumbHelper,
        AdapterInterface $connection = null
    ) {
        $this->hatimeriaCategoryHelper = $hatimeriaCategoryHelper;
        $this->hatimeriaBreadcrumbHelper = $hatimeriaBreadcrumbHelper;
        return parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $eavConfig,
            $resource,
            $eavEntityFactory,
            $resourceHelper,
            $universalFactory,
            $storeManager,
            $connection
        );
    }

    /**
     * Load collection
     *
     * @param bool $printQuery
     * @param bool $logQuery
     * @return $this
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function load($printQuery = false, $logQuery = false)
    {
        if ($this->isLoaded()) {
            return $this;
        }
        $this->addAttributeToSelect('url_path');
        $this->addAttributeToSelect('url_key');
        $this->addAttributeToSelect('image');

        parent::load($printQuery, $logQuery);

        foreach ($this->_items as $category) { /** @var CategoryModel $category */
            $this->hatimeriaCategoryHelper->addImageAttribute($category);
            $this->hatimeriaBreadcrumbHelper->addCategoryBreadcrumbs($category, $this);
        }

        return $this;
    }

    public function getItems()
    {
        if (! $this->isLoaded()) {
            $this->load();
        }

        return $this->_items;
    }


    /**
     * Retrieve item by id
     *
     * @param   mixed $idValue
     * @return  \Magento\Framework\DataObject
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getItemById($idValue)
    {
        if (! $this->isLoaded()) {
            $this->load();
        }

        if (isset($this->_items[$idValue])) {
            return $this->_items[$idValue];
        }
        return null;
    }

}
