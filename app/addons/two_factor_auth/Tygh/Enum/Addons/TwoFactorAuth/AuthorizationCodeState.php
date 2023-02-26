<?php

namespace Tygh\Enum\Addons\TwoFactorAuth;

/**
 * The class declares available parsing process statuses.
 *
 * @package Tygh\Enum\Addons\TwoFactorAuth
 */
class AuthorizationCodeState
{
    const STARTING_QUANTITY_CODE_ENTRIES = 0;
    const MAXIMUM_QUANTITY_CODE_ENTRIES = 3;
    const CODE_EXPIRATION_TIME = 300;
}
