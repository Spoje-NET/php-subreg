<?php
/**
 * Subreg - RegisterDomain Example
 *
 * @author     Vítězslav Dvořák <info@vitexsoftware.cz>
 * @copyright  (C) 2018 Spoje.Net
 */

namespace Subreg;

require_once '../vendor/autoload.php';

\Ease\Shared::instanced()->loadConfig('../tests/config.json');

$client = new Client(\Ease\Shared::instanced()->configuration);

$unexistentDomain = strtolower(\Ease\Sand::randomString()).'.cz';

$nsHosts = array("ns.spoje.net", "ns2.spoje.net");

print_r($client->registerDomain($unexistentDomain, 'G-000001', 'G-000001',
        'G-000001', 'ukulele', $nsHosts));


$response = $client->checkDomain($unexistentDomain);

var_dump($response);
