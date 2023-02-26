<?php

namespace Tygh\Addons\TwoFactorAuth;

use Tygh\Application;
use Tygh\Enum\Addons\TwoFactorAuth\AuthorizationCodeState;
use Tygh\Enum\NotificationSeverity;
use Tygh\Enum\YesNo;
use Tygh\Tygh;

class AuthorizationCode
{
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function generateCode($user_id) {
        $code = fn_generate_code();
        Tygh::$app['session']['tf_auth']['code'] = $code;
        Tygh::$app['session']['tf_auth']['code_expires_at'] = time() + AuthorizationCodeState::CODE_EXPIRATION_TIME;

        $user_data = fn_get_user_info($user_id);

        Tygh::$app['event.dispatcher']->dispatch('profile.send_code',
            [
                'user_data' => $user_data,
                'code' => $code
            ]
        );

        if (!defined('AJAX_REQUEST')) {
            fn_set_notification(NotificationSeverity::NOTICE, __('information'), __('two_factor_auth.notification.send_code', [
                '[email]' => $user_data['email']
            ]));
        }
    }

    public function checkCode($code)
    {
        if (Tygh::$app['session']['tf_auth']['code'] === $code) {
            if (Tygh::$app['session']['tf_auth']['code_expires_at'] > time()) {
                Tygh::$app['session']['tf_auth']['code_approved'] = YesNo::YES;
                fn_login_user(Tygh::$app['session']['tf_auth']['user_id'], true);
                unset(Tygh::$app['session']['tf_auth']);

                if (defined('AJAX_REQUEST')) {
                    $ajax = Tygh::$app['ajax'];
                    $ajax->assign('force_redirection', $_REQUEST['return_url'] ?? 'index.php');
                }

                return true;
            } else {
                if (defined('AJAX_REQUEST')) {
                    $this->displayAjaxCodeInfo(__('two_factor_auth.notification.code_validity_period'));
                } else {
                    fn_set_notification(NotificationSeverity::ERROR, __('error'), __('two_factor_auth.notification.code_validity_period'));
                }
            }
        } else {
            if (defined('AJAX_REQUEST')) {
                $this->displayAjaxCodeInfo(__('two_factor_auth.notification.invalid_code'));
            } else {
                fn_set_notification(NotificationSeverity::ERROR, __('error'), __('two_factor_auth.notification.invalid_code'));
            }
        }

        return false;
    }

    public function repeatCode()
    {
        if (Tygh::$app['session']['tf_auth']['number_code_requests'] >= AuthorizationCodeState::MAXIMUM_QUANTITY_CODE_ENTRIES) {
            fn_set_notification(NotificationSeverity::WARNING, __('warning'), __('two_factor_auth.notification.limit_code'));
            unset(Tygh::$app['session']['tf_auth']);

            if (defined('AJAX_REQUEST')) {
                $ajax = Tygh::$app['ajax'];
                $ajax->assign('force_redirection', fn_url('auth.login_form'));
            }

            return true;
        } else {
            $this->generateCode(Tygh::$app['session']['tf_auth']['user_id']);
            Tygh::$app['session']['tf_auth']['number_code_requests']++;

            if (!defined('AJAX_REQUEST')) {
                return true;
            }
        }

        return false;
    }

    protected function displayAjaxCodeInfo($code_info = null) {
        $view = Tygh::$app['view'];

        $view->assign([
            'style'             => 'popup',
            'code_info'         => $code_info,
            'id'                => $_REQUEST['login_block_id']
        ]);

        if ($view->templateExists('addons/two_factor_auth/views/auth/confirm_code.tpl')) {
            $view->display('addons/two_factor_auth/views/auth/confirm_code.tpl');
        }
    }
}

