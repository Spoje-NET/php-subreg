<?php

declare(strict_types=1);

/**
 * This file is part of the PHPSubreg package
 *
 * https://github.com/Spoje-NET/php-subreg
 *
 * (c) Vítězslav Dvořák <http://spojenet.cz/>
 *
 * Check domain expiry, parking NS, DNS zone presence and autorenew credit.
 *
 * Usage: php DomainHealth.php example.com
 */

require_once dirname(__DIR__).'/vendor/autoload.php';

\Ease\Shared::init([
    'SUBREG_LOCATION',
    'SUBREG_URI',
    'SUBREG_LOGIN',
    'SUBREG_PASSWORD',
], dirname(__DIR__).'/.env');

$domain = $argv[1] ?? null;

if ($domain === null) {
    fwrite(STDERR, "usage: php DomainHealth.php <domain>\n");

    exit(2);
}

$client = new \Subreg\Client(['autologin' => true] + \Subreg\Client::env2conf());

$info = $client->infoDomain($domain);
$credit = $client->getCredit();
$dnsInfo = $client->getDnsInfo($domain);
$parked = \Subreg\Client::hostsIndicateParking($info['hosts'] ?? []);
$canAutorenew = \Subreg\Client::isCreditAboveThreshold($credit);

echo json_encode([
    'domain' => $domain,
    'exDate' => $info['exDate'] ?? null,
    'autorenew' => $info['autorenew'] ?? null,
    'hosts' => $info['hosts'] ?? [],
    'parked' => $parked,
    'rgp' => $info['rgp'] ?? null,
    'dns' => $dnsInfo,
    'credit' => $credit['credit'] ?? $credit,
    'can_autorenew' => $canAutorenew,
    'hints' => array_values(array_filter([
        $parked ? 'Domain NS point at parking (parktons) — renew and/or restore Gransy NS' : null,
        !$canAutorenew ? 'Credit below threshold — autorenew will be skipped even if balance covers one renew' : null,
        empty($dnsInfo['in_zone']) ? 'Get_DNS_Info.in_zone=0 — zone may not be published on Gransy DNS' : null,
    ])),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n";
