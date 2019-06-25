<?php
declare(strict_types=1);

/** @var $page \Magento\Cms\Model\Page */
$page = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Cms\Model\Page::class);
$page
    ->setId(20)
    ->setTitle('Cms Page 100')
    ->setIdentifier('page100')
    ->setStores([0])
    ->setIsActive(1)
    ->setContent('<h1>Cms Page 100 Title</h1>')
    ->setPageLayout('1column')
    ->setMetaTitle('meta title Cms Page 100')
    ->setMetaDescription('meta description Cms Page 100')
    ->setMetaKeywords('meta keywords Cms Page 100')
    ->save();

$page = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Cms\Model\Page::class);
$page
    ->setId(21)
    ->setTitle('Cms Page Design Blank')
    ->setIdentifier('page_design_blank')
    ->setStores([0])
    ->setIsActive(1)
    ->setContent('<h1>Cms Page Design Blank Title</h1>')
    ->setPageLayout('1column')
    ->setCustomTheme('Magento/blank')
    ->setMetaTitle('meta title Cms Page Design Blank')
    ->setMetaDescription('meta description Cms Page Design Blank')
    ->setMetaKeywords('meta keywords Cms Page Design Blank')
    ->save();
