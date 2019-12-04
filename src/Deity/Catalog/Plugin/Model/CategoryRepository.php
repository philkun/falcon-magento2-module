<?php
declare(strict_types=1);

namespace Deity\Catalog\Plugin\Model;

use Magento\Catalog\Api\Data\CategoryExtensionInterface;
use Magento\Catalog\Api\Data\CategoryInterface;

/**
 * Class CategoryRepository
 *
 * @package Deity\Catalog\Plugin\Model
 */
class CategoryRepository
{
    /**
     * Get info about category by category id
     *
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $subject
     * @param CategoryInterface $result
     * @return \Magento\Catalog\Api\Data\CategoryInterface
     */
    public function afterGet(\Magento\Catalog\Api\CategoryRepositoryInterface $subject, $result)
    {
        if ($result->getImage() == '') {
            return $result;
        }
        /** @var  CategoryExtensionInterface $extensionAttributes */
        $extensionAttributes = $result->getExtensionAttributes(); /** get current extension attributes from entity **/
        $extensionAttributes->setImageUrl($result->getImageUrl());
        $result->setExtensionAttributes($extensionAttributes);

        return $result;
    }
}
