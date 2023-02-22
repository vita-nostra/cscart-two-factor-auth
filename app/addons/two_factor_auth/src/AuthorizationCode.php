<?php

namespace Tygh\Addons\TwoFactorAuth;

use Tygh\Application;
use Tygh\Enum\ImagePairTypes;
use Tygh\Enum\ObjectStatuses;
use Tygh\Enum\SiteArea;
use Tygh\Enum\YesNo;
use Tygh\Languages\Languages;
use Tygh\Registry;

class AuthorizationCode
{
    protected $db;

    protected $area;

    protected $lang_code;

    /**
     * @param Application $app
     * @param $area
     * @param $lang_code
     */
    public function __construct(Application $app, $area = AREA, $lang_code = CART_LANGUAGE)
    {
        $this->db = $app['db'];
        $this->area = $area;
        $this->lang_code = $lang_code;
    }
}

