<?php

declare(strict_types=1);

/**
 * This file is part of the PHPSubreg package
 *
 * https://github.com/Spoje-NET/php-subreg
 *
 * (c) Vítězslav Dvořák <http://spojenet.cz/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (file_exists('../vendor/autoload.php')) {
    include_once '../vendor/autoload.php';
} else {
    if (file_exists('vendor/autoload.php')) { // For Test Generator
        include_once 'vendor/autoload.php';
    }
}

/**
 * Write logs as:
 */
if (!\defined('EASE_APPNAME')) {
    \define('EASE_APPNAME', 'SubregUnitTest');
}

if (!\defined('EASE_LOGGER')) {
    \define('EASE_LOGGER', 'syslog');
}

\Ease\Shared::instanced()->loadConfig('config.json', true);
