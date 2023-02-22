<?php

namespace Tygh\Addons\TwoFactorAuth;

use Tygh\Application;
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
        $this->app['session']['tf_auth']['code'] = $code;
        $this->app['session']['tf_auth']['code_expires_at'] = time() + 5 * 60;
        fn_print_r($code);
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

                return [CONTROLLER_STATUS_REDIRECT, '', true];
            } else {
                if (defined('AJAX_REQUEST')) {
                    $this->displayAjaxCodeInfo(__('Срок действия кода истек'));
                } else {
                    fn_set_notification('E', __('error'), __('Срок действия кода истек'));
                }
            }
        } else {
            if (defined('AJAX_REQUEST')) {
                $this->displayAjaxCodeInfo(__('Неверно введен проверочный код'));
            } else {
                fn_set_notification('E', __('error'), __('Неверно введен проверочный код'));
            }
        }
    }

    public function repeatCode()
    {
        if (Tygh::$app['session']['tf_auth']['number_code_requests'] >= 1) {
            fn_set_notification('E', __('error'), __('Лимит попыток исчерпан. Пожалуйста, введите логин и пароль еще раз'));
            unset(Tygh::$app['session']['tf_auth']);

            if (defined('AJAX_REQUEST')) {
                $ajax = Tygh::$app['ajax'];
                $ajax->assign('force_redirection', fn_url('auth.login_form'));
            }

            return [CONTROLLER_STATUS_REDIRECT, 'auth.login_form', true];
        } else {
            $this->generateCode(Tygh::$app['session']['tf_auth']['user_id']);
            Tygh::$app['session']['tf_auth']['number_code_requests']++;

            if (!defined('AJAX_REQUEST')) {
                return [CONTROLLER_STATUS_REDIRECT, 'auth.confirm_code', true];
            }
        }
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

