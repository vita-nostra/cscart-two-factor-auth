<?php

namespace Tygh\Addons\TwoFactorAuth;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tygh\Addons\TwoFactorAuth\HookHandlers\AuthHookHandler;
use Tygh\Tygh;

/**
 * Class ServiceProvider is intended to register services and components of the "TwoFactorAuth" add-on to the application
 * container.
 *
 * @package Tygh\Addons\TwoFactorAuth
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritDoc
     */
    public function register(Container $app)
    {
        $app['addons.two_factor_auth.authorization_code'] = function (Container $app) {
            return new AuthorizationCode($app, AREA, DESCR_SL);
        };

        $app['addons.two_factor_auth.hook_handlers.auth'] = function (Container $app) {
            return new AuthHookHandler($app);
        };
    }

    /**
     * @return Service
     */
    public static function getAuthorizationCode()
    {
        return Tygh::$app['addons.two_factor_auth.authorization_code'];
    }
}

