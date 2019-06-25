<?php
declare(strict_types=1);

namespace Deity\Cms\Model;

use Deity\Cms\Model\Template\Filter;
use Deity\CmsApi\Api\Data\BlockInterface;
use Deity\CmsApi\Api\Data\BlockInterfaceFactory;
use Deity\CmsApi\Api\GetStaticBlockDataInterface;
use Deity\CmsApi\Model\GetBlockByIdentifierInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class GetStaticBlockContent
 *
 * @package Deity\Cms\Model
 */
class GetStaticBlockData implements GetStaticBlockDataInterface
{

    /**
     * @var GetBlockByIdentifierInterface
     */
    private $getBlockByIdentifier;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var BlockInterfaceFactory
     */
    private $blockFactory;

    /**
     * @var Filter
     */
    private $contentFilter;

    /**
     * GetStaticBlockContent constructor.
     * @param GetBlockByIdentifierInterface $getBlockByIdentifier
     * @param StoreManagerInterface $storeManager
     * @param Filter $filterEmulate
     * @param BlockInterfaceFactory $blockFactory
     */
    public function __construct(
        GetBlockByIdentifierInterface $getBlockByIdentifier,
        StoreManagerInterface $storeManager,
        Filter $filterEmulate,
        BlockInterfaceFactory $blockFactory
    ) {
        $this->blockFactory = $blockFactory;
        $this->contentFilter = $filterEmulate;
        $this->getBlockByIdentifier = $getBlockByIdentifier;
        $this->storeManager = $storeManager;
    }

    /**
     * Get content of the static block
     *
     * @param string $identifier
     * @return \Deity\CmsApi\Api\Data\BlockInterface
     * @throws NoSuchEntityException
     */
    public function execute(string $identifier): BlockInterface
    {
        $storeId = (int)$this->storeManager->getStore()->getId();
        $blockInstance = $this->getBlockByIdentifier->execute($identifier, $storeId);
        $blockContent =(string)$this->contentFilter->filter($blockInstance->getContent());
        return $this->blockFactory->create(
            [
                'content' => $blockContent
            ]
        );
    }
}
