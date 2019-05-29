<?php
/**
 * Subreg - Testing Bootstrap
 *
 * @author     Vítězslav Dvořák <info@vitexsoftware.cz>
 * @copyright  (C) 2018 Spoje.Net
 */


if (file_exists('../vendor/autoload.php')) {
    include_once '../vendor/autoload.php';
} else {
    if (file_exists('vendor/autoload.php')) { //For Test Generator
        include_once 'vendor/autoload.php';
    }
}
/**
 * Write logs as:
 */
if (!defined('EASE_APPNAME')) {
    define('EASE_APPNAME', 'SubregUnitTest');
}
if (!defined('EASE_LOGGER')) {
    define('EASE_LOGGER', 'syslog');
}

\Ease\Shared::instanced()->loadConfig('config.json',true);

