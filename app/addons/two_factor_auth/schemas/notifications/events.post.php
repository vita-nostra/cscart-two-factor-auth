<?php

use Tygh\Enum\SiteArea;
use Tygh\Notifications\DataValue;
use Tygh\Notifications\Transports\Mail\MailMessageSchema;
use Tygh\Enum\UserTypes;
use Tygh\Notifications\Transports\Mail\MailTransport;

defined('BOOTSTRAP') or die('Access denied');

$schema['profile.send_code'] = [
    'id'        => 'profile.send_code',
    'group'     => 'profile',
    'name'      => [
        'template' => 'profile.event.send_code',
        'params'   => [
        ],
    ],
    'receivers' => [
        UserTypes::CUSTOMER => [
            MailTransport::getId() => MailMessageSchema::create([
                'area'            => SiteArea::STOREFRONT,
                'from'            => 'default_company_users_department',
                'to'              => DataValue::create('user_data.email'),
                'template_code'   => 'two_factor_auth_notification',
                'legacy_template' => 'addons/two_factor_auth/two_factor_auth.tpl',
                'language_code'   => DataValue::create('lang_code', CART_LANGUAGE),
            ]),
        ],
    ],
];

return $schema;

