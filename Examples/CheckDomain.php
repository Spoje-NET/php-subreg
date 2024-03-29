<?php
/**
 * Subreg - Usage Example
 *
 * @author     Vítězslav Dvořák <info@vitexsoftware.cz>
 * @copyright  (C) 2018 Spoje.Net
 */

namespace Subreg;

require_once '../vendor/autoload.php';

\Ease\Shared::instanced()->loadConfig('../config.json');

$client = new Client(\Ease\Shared::instanced()->configuration);


$unexistentDomain = strtolower(\Ease\Functions::randomString()).'.cz';

$response = $client->checkDomain($unexistentDomain);

$existingDomain   = 'spoje.net';

$response = $client->checkDomain($existingDomain);
var_dump($response);
