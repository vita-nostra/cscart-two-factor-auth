<?php

use Tygh\Addons\TwoFactorAuth\ServiceProvider;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

$authorization_code = ServiceProvider::getAuthorizationCode();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($mode == 'code') {
        if (!isset(Tygh::$app['session']['tf_auth'])) {
            return [CONTROLLER_STATUS_REDIRECT, 'auth.login_form', true];
        }
        $authorization_code->checkCode($_POST['confirm_code']);
    }
}

if ($mode == 'confirm_code') {
    if (!isset(Tygh::$app['session']['tf_auth'])) {
        return [CONTROLLER_STATUS_REDIRECT, 'auth.login_form', true];
    }
}

if ($mode == 'repeat_confirm_code') {
    if (isset(Tygh::$app['session']['tf_auth'])) {
        $authorization_code->repeatCode();
    }

    if (!defined('AJAX_REQUEST')) {
        return [CONTROLLER_STATUS_REDIRECT, 'auth.confirm_code', true];
    }
}