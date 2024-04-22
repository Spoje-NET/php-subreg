<?php

/**
 * Subreg - Usage Example
 *
 * @author     Vítězslav Dvořák <info@vitexsoftware.cz>
 * @copyright  (C) 2024 Spoje.Net
 */

namespace Subreg;

require_once '../vendor/autoload.php';

\Ease\Shared::init([
    'SUBREG_LOCATION',
    'SUBREG_URI',
    'SUBREG_LOGIN',
    'SUBREG_PASSWORD'
        ], '../.env');

$client = new Client(Client::env2conf(\Ease\Shared::instanced()->configuration));
$client->login();

$response = $client->getDnsZone('vitexsoftware.com');

var_dump($response);
