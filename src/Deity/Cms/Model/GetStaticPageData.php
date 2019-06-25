<?php
declare(strict_types=1);

namespace Deity\Cms\Model;

use Deity\Cms\Model\Template\Filter;
use Deity\CmsApi\Api\Data\PageInterface;
use Deity\CmsApi\Api\Data\PageInterfaceFactory;
use Deity\CmsApi\Api\GetStaticPageDataInterface;
use Magento\Cms\Api\PageRepositoryInterface;

/**
 * Class GetStaticPageData
 *
 * @package Deity\Cms\Model
 */
class GetStaticPageData implements GetStaticPageDataInterface
{

    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    /**
     * @var PageInterfaceFactory
     */
    private $pageFactory;

    /**
     * @var Filter
     */
    private $filter;

    /**
     * GetStaticPageData constructor.
     * @param PageRepositoryInterface $pageRepository
     * @param PageInterfaceFactory $pageFactory
     * @param Filter $filter
     */
    public function __construct(
        PageRepositoryInterface $pageRepository,
        PageInterfaceFactory $pageFactory,
        Filter $filter
    ) {
        $this->pageRepository = $pageRepository;
        $this->pageFactory = $pageFactory;
        $this->filter = $filter;
    }

    /**
     * Get static page content object
     *
     * @param int $pageId
     * @return \Deity\CmsApi\Api\Data\PageInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(int $pageId): PageInterface
    {
        $pageObject = $this->pageRepository->getById($pageId);
        $filteredPageContent = $this->filter->filter($pageObject->getContent());
        return $this->pageFactory->create(
            [
                'content' => (string)$filteredPageContent,
                'metaTitle' => (string)$pageObject->getMetaTitle(),
                'metaDescription' => (string)$pageObject->getMetaDescription(),
                'metaKeywords' => (string)$pageObject->getMetaKeywords()
            ]
        );
    }
}
