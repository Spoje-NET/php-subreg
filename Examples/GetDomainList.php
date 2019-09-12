<?php
/**
 * Subreg - Usage Example
 *
 * @author     Vítězslav Dvořák <info@vitexsoftware.cz>
 * @copyright  (C) 2018 Spoje.Net
 */

namespace Subreg;

require_once '../vendor/autoload.php';

\Ease\Shared::instanced()->loadConfig('../tests/config.json');

$client = new Client(\Ease\Shared::instanced()->configuration);

$response = $client->call('Domains_List');

var_dump($response);
