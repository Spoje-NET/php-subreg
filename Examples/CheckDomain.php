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

namespace Subreg;

require_once '../vendor/autoload.php';

\Ease\Shared::init(['SUBREG_LOCATION', 'SUBREG_URI', 'SUBREG_LOGIN', 'SUBREG_PASSWORD'], '../.env');

$client = new Client(Client::env2conf(\Ease\Shared::instanced()->configuration));

$unexistentDomain = strtolower(\Ease\Functions::randomString()).'.cz';

$response = $client->checkDomain($unexistentDomain);

$existingDomain = 'spoje.net';

$response = $client->checkDomain($existingDomain);
var_dump($response);

/** 
 array(5) {
  'name' =>
  string(9) "spoje.net"
  'avail' =>
  int(0)
  'price' =>
  array(3) {
    'amount' =>
    string(6) "291.32"
    'premium' =>
    int(0)
    'currency' =>
    string(3) "CZK"
  }
  'price_renew' =>
  array(3) {
    'amount' =>
    string(6) "291.32"
    'premium' =>
    int(0)
    'currency' =>
    string(3) "CZK"
  }
  'price_transfer' =>
  array(3) {
    'amount' =>
    string(6) "291.32"
    'premium' =>
    int(0)
    'currency' =>
    string(3) "CZK"
  }
}
 */