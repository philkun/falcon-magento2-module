<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\SearchCriteria\CollectionProcessor\FilterProcessor;

use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor\FilterProcessor\CustomFilterInterface;
use Magento\Framework\Data\Collection\AbstractDb;

/**
 * Class ProductCategoryFilter
 *
 * @package Deity\Catalog\Model\SearchCriteria\CollectionProcessor\FilterProcessor
 */
class ProductCategoryFilter implements CustomFilterInterface
{

    /**
     * @var CategoryFactory
     */
    private $categoryFactory;

    /**
     * ProductCategoryFilter constructor.
     * @param CategoryFactory $categoryFactory
     */
    public function __construct(CategoryFactory $categoryFactory)
    {
        $this->categoryFactory = $categoryFactory;
    }
    
    /**
     * @inheritDoc
     */
    public function apply(Filter $filter, AbstractDb $collection)
    {
        $categoryId = $filter->getValue();

        $categoryObject = $this->categoryFactory->create();
        $categoryObject->setId($categoryId);
        $categoryObject->setIsAnchor(true);
        /** @var Collection $collection */
        $collection->addCategoryFilter($categoryObject);

        return true;
    }
}
