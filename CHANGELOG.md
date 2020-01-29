**Note:** This is a cumulative changelog that outlines all of the changes to all magento modules in Deity [src/Deity](./src/Deity) namespace.

Versions marked with a number and date (e.g. v0.1.0 (2018-10-05)) are already released and available via packagist. Versions without a date are not released yet.
## v5.3.2 (2020-01-29)
 - Fixed the issue with empty category filter when category filter is selected.
## v5.3.0 (2020-01-20)
 - Extended category url to Category REST API response
 - Introduced email template postprocessor to replace all urls to magento with falcon urls [#130](https://github.com/deity-io/falcon-magento2-development/issues/130)
 - Fixed the issue with category filter. For categories that were anchor.
## v5.2.0 (2019-10-29)
 -  Added new endpoint to preset Falcon Domain url and Falcon cache flush url  [#129](https://github.com/deity-io/falcon-magento2-development/issues/129)
## v5.1.3 (2019-10-21)
 - Fixed the issue when selected price filter option was not returned by API
 - Update module according to the latest magento release 2.3.3
## v5.1.2 (2019-09-19)
 - Updated search API: search term is no longer a required argument.
 - Introduced new API for validating password token.
 - Fixed the issue with retrieving product url path within category context caused by change introduced in m 2.3.1, 2.3.2
## v5.1.1 (2019-09-02)
 - Updated codebase according to new coding standard of Magento 2.3.2
 - Fixed few issues with test fixtures for Magento 2.3.2
 - Fixed the issue with empty catalog product response when page size is not specified
## v5.1.0 (2019-06-25)
 -  Added new endpoint that returns version and default store code [#89](https://github.com/deity-io/falcon-magento2-development/issues/89)
 -  Added cache invalidation hook [#107](https://github.com/deity-io/falcon-magento2-development/issues/107)
 -  Added API for providing static block and static pages info [#108](https://github.com/deity-io/falcon-magento2-development/issues/108)
## v5.0.1 (2019-06-03)
 - Removed `category_ids` from the blacklist of attributes for Product API.
## v5.0.0 (2019-05-29)
 - Added search API endpoints. `falcon/catalog-search` and `falcon/catalog-search/autocomplete` [#36](https://github.com/deity-io/falcon-magento2-development/issues/36)
 - Introduced new endpoint to get product detail information `falcon/products/:sku` [#97](https://github.com/deity-io/falcon-magento2-development/issues/97)
 - Refactored Configurable Product Data provider also within the context of new API [#97](https://github.com/deity-io/falcon-magento2-development/issues/97)
 - Fixed the issue with zero cart total when removing items from the cart [#103](https://github.com/deity-io/falcon-magento2-development/issues/103)
 - Introduced `custom-attributes` field to product listing and product detail API [#102](https://github.com/deity-io/falcon-magento2-development/issues/102)
## v4.0.2 (2019-04-12)
 - Fixed the issue with price filters [#95](https://github.com/deity-io/falcon-magento2-development/issues/95)
## v4.0.1 (2019-04-11)
 - Fixed back compatibility issue with Magento 2.2
## v4.0.0 (2019-04-02)
 - Introduced new API param to merge guest shopping cart into customer one, after login [#23](https://github.com/deity-io/falcon-magento2-development/issues/23)
 - fixed the issue with zero grandtotal for logged in customer [#91](https://github.com/deity-io/falcon-magento2-development/issues/91)
 - few fixes to support 2.3.1 Magento version
 - fixed the issue for menu API when no categories exist yet [#85](https://github.com/deity-io/falcon-magento2-development/issues/85)
 - moved all REST API's to dedicated `falcon` namespace [#84](https://github.com/deity-io/falcon-magento2-development/issues/84)
 - updated place order API for guest customer [#80] (https://github.com/deity-io/falcon-magento2-development/issues/80)
 - added selected aggregation to catalog product list REST API [#72](https://github.com/deity-io/falcon-magento2-development/issues/72)
 - fixed few issues for Paypal REST API
## v3.1.0 (2019-03-13)
 - Introduced Paypal REST API
 - Updated guest ORDER REST API to return `masked_order_id` instead of `order_id`
 - Added `is_selected` field to filter options.
 - Selected filter are also returned with available filter options.
 - Fixed the issue with category filter.
## v3.0.0 (2019-02-14)
 - Removed `V1/contact` API
 - Removed `V1/info` API
 - Renamed customer order API from `/V1/carts/mine/deity-order` to `/V1/carts/mine/place-order`
 - Introduced API tests for customer order API
 - Renamed guest order API from `/V1/guest-carts/:cartId/deity-order` to `/V1/guest-carts/:cartId/place-order`
 - Introduced API tests for guest order API
 - Renamed customer payment API from `/V1/carts/mine/payment-information` to `/V1/carts/mine/save-payment-information-and-order`
 - Introduced API tests for customer payment
 - Renamed guest payment API from `/V1/guest-carts/:cartId/payment-information` to `/V1/guest-carts/:cartId/save-payment-information-and-order`
 - Introduced API tests for guest payment
 - Fixed the issue with guest order placement
 - Refactored Order Id Mask classes. Introduced interfaces, repository. Introduced API tests.
## v2.0.1
 - Added product images to checkout `totals` API
## v2.0
 - refactored BreadCrumbs API
 - Introduced new menu API
 - Introduced new category products API
 - Removed plugin for magento token API
 - Added UrlRewrite API
 - fixed the issues with swagger. Swagger page is functional.
 - cleaned up custom plugins and changes not relevant for Falcon product. ([#11](https://github.com/deity-io/falcon-magento2-development/pull/11)) ([#10](https://github.com/deity-io/falcon-magento2-development/pull/10))
 - fixed the issue with installing module on Magento EE edition.
### Deity_UrlRewriteApi v1.0.0
 - Existing interfaces extracted to dedicated module
 - changed the `/url` API specification, ambiguous fields, `cms`, `product`, `custom`, `category` removed.
 - new version returns [`entity_type`, `entity_id`, `canonical_url`]. See swagger for more details
 - introduced API tests
### Deity_Store v1.0.0
 - existing interface extracted to dedicated namespace
 - added `api_version` to existing API
 - introduced API tests ([#7](https://github.com/deity-io/falcon-magento2-development/pull/7))
 
## v1.0.2 (2018-10-05)

- Add endpoints for newsletter subscribe and unsubscribe
- Fix class name collisions in `Deity\MagentoApi\Helper\Breadcrumbs`

## v1.0.0

- Deity_MagentoApi initial release