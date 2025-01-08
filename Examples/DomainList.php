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

$client = new Client();

$response = $client->call('Domains_List');

var_dump($response);
