<?php

namespace Tygh\Addons\TwoFactorAuth\HookHandlers;

use Tygh\Application;

/**
 * This class describes the hook handlers related to the authorization add-on
 *
 * @package Tygh\Addons\TwoFactorAuth\HookHandlers
 */
class AuthHookHandler
{
    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function onLoginUserPre(&$user_id, &$udata, &$auth, &$condition)
    {

    }
}