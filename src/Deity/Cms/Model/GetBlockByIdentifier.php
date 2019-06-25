<?php
declare(strict_types=1);

namespace Deity\Cms\Model;

use Deity\CmsApi\Model\GetBlockByIdentifierInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Cms\Model\BlockFactory;
use Magento\Cms\Model\ResourceModel\Block;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class GetBlockByIdentifier
 *
 * @package Deity\Cms\Model
 */
class GetBlockByIdentifier implements GetBlockByIdentifierInterface
{

    /**
     * @var BlockFactory
     */
    private $blockFactory;

    /**
     * @var Block
     */
    private $blockResource;

    /**
     * GetBlockByIdentifier constructor.
     * @param BlockFactory $blockFactory
     * @param Block $blockResource
     */
    public function __construct(
        BlockFactory $blockFactory,
        Block $blockResource
    ) {
        $this->blockFactory = $blockFactory;
        $this->blockResource = $blockResource;
    }

    /**
     * Get block by identifier for given storeId
     *
     * @param string $identifier
     * @param int $storeId
     * @return BlockInterface
     * @throws NoSuchEntityException
     */
    public function execute(string $identifier, int $storeId): BlockInterface
    {
        $block = $this->blockFactory->create();
        $block->setStoreId($storeId);
        $this->blockResource->load($block, $identifier, BlockInterface::IDENTIFIER);

        if (!$block->getId()) {
            throw new NoSuchEntityException(__('The CMS block with the "%1" ID doesn\'t exist.', $identifier));
        }

        return $block;
    }
}
