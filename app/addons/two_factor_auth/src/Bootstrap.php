<?php

namespace Tygh\Addons\TwoFactorAuth;

use Tygh\Core\ApplicationInterface;
use Tygh\Core\BootstrapInterface;

/**
 * This class describes instructions for loading the TwoFactorAuth add-on
 *
 * @package Tygh\Addons\TwoFactorAuth
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritDoc
     */
    public function boot(ApplicationInterface $app)
    {
        $app->register(new ServiceProvider());
    }

    /**
     * @inheritDoc
     */
    public function getHookHandlerMap()
    {
        return [
            'login_user_pre' => [
                'addons.two_factor_auth.hook_handlers.auth',
                'onLoginUserPre'
            ]
        ];
    }
}

