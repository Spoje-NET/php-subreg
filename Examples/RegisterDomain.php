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

$unexistentDomain = strtolower(\Ease\Sand::randomString()).'.cz';

$nsHosts = ['ns.spoje.net', 'ns2.spoje.net'];

print_r($client->registerDomain(
    $unexistentDomain,
    'G-000001',
    'G-000001',
    'G-000001',
    'ukulele',
    $nsHosts,
));

$response = $client->checkDomain($unexistentDomain);

var_dump($response);
