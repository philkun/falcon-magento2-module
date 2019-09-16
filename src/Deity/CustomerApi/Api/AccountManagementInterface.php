<?php
declare(strict_types=1);

namespace Deity\CustomerApi\Api;

/**
 * Interface AccountManagementInterface
 *
 * @package Deity\CustomerApi\Api
 */
interface AccountManagementInterface
{
    /**
     * Validate given password link token
     *
     * @param int $customerId
     * @param string $resetPasswordLinkToken
     * @return bool
     */
    public function validateResetPasswordLinkToken(int $customerId, string $resetPasswordLinkToken): bool;
}
