<?php
declare(strict_types=1);

namespace Deity\Customer\Model;

use Deity\CustomerApi\Api\AccountManagementInterface;
use Magento\Customer\Api\AccountManagementInterface as MagentoAccountManagementInterface;

/**
 * Class AccountManagement
 *
 * @package Deity\Customer\Model
 */
class AccountManagement implements AccountManagementInterface
{

    /**
     * @var MagentoAccountManagementInterface
     */
    private $customerAccountManagement;

    /**
     * AccountManagement constructor.
     * @param MagentoAccountManagementInterface $customerAccountManagement
     */
    public function __construct(MagentoAccountManagementInterface $customerAccountManagement)
    {
        $this->customerAccountManagement = $customerAccountManagement;
    }

    /**
     * Validate given password link token
     *
     * @param int $customerId
     * @param string $resetPasswordLinkToken
     * @return bool
     *
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\State\ExpiredException
     * @throws \Magento\Framework\Exception\State\InputMismatchException
     */
    public function validateResetPasswordLinkToken(int $customerId, string $resetPasswordLinkToken): bool
    {
        if ($customerId === 0) {
            //convert zero value to null to make sure we can validate token without customerId
            $customerId = null;
        }

        return $this->customerAccountManagement->validateResetPasswordLinkToken($customerId, $resetPasswordLinkToken);
    }
}
