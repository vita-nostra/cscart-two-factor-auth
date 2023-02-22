<?php

namespace Tygh\Addons\TwoFactorAuth\HookHandlers;

use Tygh\Addons\TwoFactorAuth\ServiceProvider;
use Tygh\Application;
use Tygh\Tygh;

/**
 * This class describes the hook handlers related to the authorization add-on
 *
 * @package Tygh\Addons\TwoFactorAuth\HookHandlers
 */
class AuthHookHandler
{
    protected Application $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * The "login_user_pre" hook handler.
     *
     * Actions performed:
     *  - Replaces $object_id (the identifier of a product) with the identifier of its parent product, if available.
     *      This displays the attachments of the parent product on the pages of its child variations.
     *
     * @see fn_login_user
     */
    public function onLoginUserPre(&$user_id, &$udata, &$auth, &$condition)
    {
        $authorization_code = ServiceProvider::getAuthorizationCode();
        if (AREA === 'C') {
            if (defined('AJAX_REQUEST') && !empty($_POST)) {
                $authorization_code->displayAjaxCodeInfo();
            }
            if (!isset(Tygh::$app['session']['tf_auth']['code_approved']) && !empty($auth) && !empty($_POST)) {
                Tygh::$app['session']['tf_auth']['user_id'] = $user_id;
                Tygh::$app['session']['tf_auth']['number_code_requests'] = 0;
                $authorization_code->generateCode($user_id);

                fn_redirect('auth.confirm_code');
            }
        }
    }
}