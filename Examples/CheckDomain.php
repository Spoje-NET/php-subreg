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


$unexistentDomain = strtolower(\Ease\Sand::randomString()).'.cz';

$response = $client->checkDomain($unexistentDomain);

$existingDomain   = 'spoje.net';

$response = $client->checkDomain($existingDomain);
var_dump($response);
