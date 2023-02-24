<?php

namespace Tygh\Addons\TwoFactorAuth\HookHandlers;

use Tygh\Addons\TwoFactorAuth\ServiceProvider;
use Tygh\Application;
use Tygh\Enum\Addons\TwoFactorAuth\AuthorizationCodeState;
use Tygh\Enum\SiteArea;
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
     *  - After successful authorization, an additional verification is added using a code that is sent to the email
     *
     * @see fn_login_user
     */
    public function onLoginUserPre(&$user_id, &$udata, &$auth, &$condition)
    {
        $authorization_code = ServiceProvider::getAuthorizationCode();
        if (AREA === SiteArea::STOREFRONT) {
            if (defined('AJAX_REQUEST')) {
                $view = Tygh::$app['view'];

                $view->assign([
                    'style'             => 'popup',
                    'code_info'         => false,
                    'id'                => $_REQUEST['login_block_id']
                ]);

                if ($view->templateExists('addons/two_factor_auth/views/auth/confirm_code.tpl')) {
                    $view->display('addons/two_factor_auth/views/auth/confirm_code.tpl');
                }
            }
            if (!isset(Tygh::$app['session']['tf_auth']['code_approved']) && !empty($auth) && !empty($_POST)) {
                Tygh::$app['session']['tf_auth']['user_id'] = $user_id;
                Tygh::$app['session']['tf_auth']['number_code_requests'] = AuthorizationCodeState::STARTING_QUANTITY_CODE_ENTRIES;
                $authorization_code->generateCode($user_id);

                fn_redirect('auth.confirm_code');
            }
        }
    }
}