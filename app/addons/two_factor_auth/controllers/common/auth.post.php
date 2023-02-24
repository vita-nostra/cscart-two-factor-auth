<?php

use Tygh\Addons\TwoFactorAuth\ServiceProvider;
use Tygh\Enum\NotificationSeverity;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

$authorization_code = ServiceProvider::getAuthorizationCode();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($mode == 'code') {
        if (!isset(Tygh::$app['session']['tf_auth'])) {
            return [CONTROLLER_STATUS_REDIRECT, 'auth.login_form', true];
        }
        if($authorization_code->checkCode($_POST['confirm_code'])) {
            return [CONTROLLER_STATUS_REDIRECT, '', true];
        }
    }
}

if ($mode == 'confirm_code') {
    if (!isset(Tygh::$app['session']['tf_auth'])) {
        return [CONTROLLER_STATUS_REDIRECT, 'auth.login_form', true];
    }
}

if ($mode == 'repeat_confirm_code') {
    if (isset(Tygh::$app['session']['tf_auth'])) {
        if($authorization_code->repeatCode()) {
            return [CONTROLLER_STATUS_REDIRECT, 'auth.confirm_code', true];
        }
    } else {
        fn_set_notification(NotificationSeverity::WARNING, __('warning'), __('two_factor_auth.notification.session_expired'));
        return [CONTROLLER_STATUS_REDIRECT, 'auth.login_form', true];
    }
}

