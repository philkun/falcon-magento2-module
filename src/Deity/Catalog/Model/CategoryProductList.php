<?php
declare(strict_types=1);

namespace Deity\Catalog\Model;

use Deity\CatalogApi\Api\CategoryProductListInterface;
use Deity\CatalogApi\Api\Data\ProductSearchResultsInterface;
use Deity\CatalogApi\Api\Data\ProductSearchResultsInterfaceFactory;
use Deity\CatalogApi\Api\ProductConvertInterface;
use Deity\CatalogApi\Api\ProductFilterProviderInterface;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\CatalogInventory\Helper\Stock;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Registry;

/**
 * Class CategoryProductList
 *
 * @package Deity\Catalog\Model
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CategoryProductList implements CategoryProductListInterface
{

    const FALCON_DEFAULT_PAGE_SIZE = 20;

    /**
     * @var ProductSearchResultsInterfaceFactory
     */
    private $productSearchResultFactory;
    
    /**
     * @var ProductConvertInterface
     */
    private $productConverter;

    /**
     * Catalog layer
     *
     * @var \Magento\Catalog\Model\Layer
     */
    private $catalogLayer;

    /**
     * @var Stock
     */
    private $stockHelper;

    /**
     * @var ProductFilterProviderInterface
     */
    private $filterProvider;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchBuilder;
    
    /**
     * CategoryProductList constructor.
     *
     * @param ProductSearchResultsInterfaceFactory $productSearchResultFactory
     * @param ProductConvertInterface $convert
     * @param Stock $stockHelper
     * @param Resolver $layerResolver
     * @param ProductFilterProviderInterface $productFilterProvider
     * @param Registry $registry
     * @param SearchCriteriaBuilder $searchBuilder
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ProductSearchResultsInterfaceFactory $productSearchResultFactory,
        ProductConvertInterface $convert,
        Stock $stockHelper,
        Resolver $layerResolver,
        ProductFilterProviderInterface $productFilterProvider,
        Registry $registry,
        SearchCriteriaBuilder $searchBuilder,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->searchBuilder = $searchBuilder;
        $this->registry = $registry;
        $this->stockHelper = $stockHelper;
        $this->collectionProcessor = $collectionProcessor;
        $this->filterProvider = $productFilterProvider;
        $this->productConverter = $convert;
        $this->productSearchResultFactory = $productSearchResultFactory;
        $this->catalogLayer = $layerResolver->get();
    }

    /**
     * @inheritdoc
     */
    public function getList(
        int $categoryId,
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null
    ): ProductSearchResultsInterface {

        if ($searchCriteria === null) {
            $searchCriteria = $this->searchBuilder
                ->setCurrentPage(1)
                ->setPageSize(self::FALCON_DEFAULT_PAGE_SIZE)
                ->create();
        } elseif ($searchCriteria->getPageSize() == 0) {
            $searchCriteria->setCurrentPage(1)->setPageSize(self::FALCON_DEFAULT_PAGE_SIZE);
        }

        $this->presetCategoryContext($categoryId, $searchCriteria);

        $this->collectionProcessor->process($searchCriteria, $this->getProductCollection());

        $responseProducts = [];
        foreach ($this->getProductCollection() as $productObject) {
            /** @var $productObject Product */
            $responseProducts[] = $this->productConverter->convert($productObject);
        }
        /** @var ProductSearchResultsInterface $productSearchResult */
        $productSearchResult = $this->productSearchResultFactory->create();

        $filters = [];
        if ($this->catalogLayer->getCurrentCategory()->getIsAnchor()) {
            $filters = $this->filterProvider->getFilterList($this->catalogLayer, $searchCriteria);
        }

        $productSearchResult->setFilters($filters);

        $productSearchResult->setItems($responseProducts);

        $productSearchResult->setTotalCount($this->getProductCollection()->getSize());

        return $productSearchResult;
    }

    /**
     * Check if category filter is applied, if so load the context for filter category
     *
     * @param int $categoryId
     * @param SearchCriteriaInterface $searchCriteria
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function presetCategoryContext(int $categoryId, SearchCriteriaInterface $searchCriteria)
    {
        foreach ($searchCriteria->getFilterGroups() as $group) {
            foreach ($group->getFilters() as $filter) {
                if ($filter->getField() === 'cat') {
                    $categoryId = $filter->getValue();
                    break 2;
                }
            }
        }

        $this->catalogLayer->setCurrentCategory($categoryId);

        $this->registry->register('current_category', $this->catalogLayer->getCurrentCategory());
    }

    /**
     * Get product collection
     *
     * @return Collection
     */
    private function getProductCollection(): Collection
    {
        $collection = $this->catalogLayer->getProductCollection();
        $this->stockHelper->addIsInStockFilterToCollection($collection);
        return $collection;
    }
}
