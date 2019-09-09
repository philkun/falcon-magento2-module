<?php
declare(strict_types=1);

namespace Deity\CatalogSearch\Model;

use Deity\Catalog\Model\CategoryProductList;
use Deity\CatalogApi\Api\Data\ProductSearchResultsInterface;
use Deity\CatalogApi\Api\Data\ProductSearchResultsInterfaceFactory;
use Deity\CatalogApi\Api\ProductConvertInterface;
use Deity\CatalogApi\Api\ProductFilterProviderInterface;
use Deity\CatalogApi\Model\Product\CollectionProviderInterface;
use Deity\CatalogSearchApi\Api\SearchInterface;
use Deity\CatalogSearchApi\Model\QueryCollectionServiceInterface;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Api\Search\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Class CatalogSearchProductList
 *
 * @package Deity\CatalogSearch\Model
 */
class CatalogSearchProductList implements SearchInterface
{
    /**
     * @var QueryCollectionServiceInterface
     */
    private $queryCollectionService;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var Resolver
     */
    private $layerResolver;

    /**
     * @var ProductSearchResultsInterfaceFactory
     */
    private $productSearchResultFactory;

    /**
     * @var ProductConvertInterface
     */
    private $productConverter;

    /**
     * @var ProductFilterProviderInterface
     */
    private $filterProvider;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchBuilder;

    /**
     * @var CollectionProviderInterface
     */
    private $collectionProvider;

    /**
     * CatalogSearchProductList constructor.
     * @param ProductSearchResultsInterfaceFactory $productSearchResultFactory
     * @param CollectionProviderInterface $collectionProvider
     * @param ProductConvertInterface $convert
     * @param Resolver $layerResolver
     * @param ProductFilterProviderInterface $productFilterProvider
     * @param CollectionProcessorInterface $collectionProcessor
     * @param CollectionFactory $collectionFactory
     * @param SearchCriteriaBuilder $searchBuilder
     * @param QueryCollectionServiceInterface $queryCollectionService
     */
    public function __construct(
        ProductSearchResultsInterfaceFactory $productSearchResultFactory,
        CollectionProviderInterface $collectionProvider,
        ProductConvertInterface $convert,
        Resolver $layerResolver,
        ProductFilterProviderInterface $productFilterProvider,
        CollectionProcessorInterface $collectionProcessor,
        CollectionFactory $collectionFactory,
        SearchCriteriaBuilder $searchBuilder,
        QueryCollectionServiceInterface $queryCollectionService
    ) {
        $this->searchBuilder = $searchBuilder;
        $this->collectionProcessor = $collectionProcessor;
        $this->filterProvider = $productFilterProvider;
        $this->productConverter = $convert;
        $this->productSearchResultFactory = $productSearchResultFactory;
        $this->collectionProvider = $collectionProvider;
        $layerResolver->create(Resolver::CATALOG_LAYER_SEARCH);
        $this->layerResolver = $layerResolver;
        $this->collectionFactory = $collectionFactory;
        $this->queryCollectionService = $queryCollectionService;
    }

    /**
     * @inheritdoc
     */
    public function search(
        string $query = '',
        SearchCriteriaInterface $searchCriteria = null
    ): ProductSearchResultsInterface {
        $responseProducts = [];
        $layer = $this->layerResolver->get();

        $collection = $this->collectionProvider->getProductCollectionFromLayer($layer);
        $collection->setOrder('relevance', 'DESC');

        $this->queryCollectionService->apply($collection, $query);

        if ($searchCriteria === null) {
            $searchCriteria = $this->searchBuilder
                ->setCurrentPage(1)
                ->setPageSize(CategoryProductList::FALCON_DEFAULT_PAGE_SIZE)
                ->create();
        } elseif ($searchCriteria->getPageSize() == 0) {
            $searchCriteria->setCurrentPage(1)->setPageSize(CategoryProductList::FALCON_DEFAULT_PAGE_SIZE);
        }

        $this->collectionProcessor->process($searchCriteria, $collection);

        foreach ($collection->getItems() as $product) {
            $responseProducts[] = $this->productConverter->convert(
                $product
            );
        }

        $productSearchResult = $this->productSearchResultFactory->create();
        $productSearchResult->setFilters(
            $this->filterProvider->getFilterList($layer, $searchCriteria)
        );
        $productSearchResult->setItems($responseProducts);
        $productSearchResult->setTotalCount($collection->getSize());

        return $productSearchResult;
    }
}
