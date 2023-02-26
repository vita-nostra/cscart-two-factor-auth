<?php

namespace Tygh\Addons\TwoFactorAuth;

use Tygh\Addons\InstallerInterface;
use Tygh\Core\ApplicationInterface;

/**
 * This class describes the instractions for installing and uninstalling the TwoFactorAuth add-on
 *
 * @package Tygh\Addons\TwoFactorAuth
 */
class Installer implements InstallerInterface
{
    /**
     * @inheritDoc
     */
    public static function factory(ApplicationInterface $app)
    {
        return new self();
    }

    /**
     * @inheritDoc
     */
    public function onInstall()
    {

    }

    /**
     * @inheritDoc
     */
    public function onUninstall()
    {

    }

    /**
     * @inheritDoc
     */
    public function onBeforeInstall()
    {

    }
}

