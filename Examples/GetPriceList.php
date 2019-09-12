<?php
/**
 * Subreg - RegisterDomain Example
 *
 * @author     Vítězslav Dvořák <info@vitexsoftware.cz>
 * @copyright  (C) 2019 Spoje.Net
 */

namespace Subreg;

require_once '../vendor/autoload.php';

\Ease\Shared::instanced()->loadConfig('../tests/config.json');

$client = new Client(\Ease\Shared::instanced()->configuration);


print_r($client->pricelist('KONCOVA'));

//print_r($client->pricelist());

