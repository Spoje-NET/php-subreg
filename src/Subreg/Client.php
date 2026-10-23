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

/**
 * Basic Soap Client class.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class Client extends \Ease\Molecule
{
    use \Ease\Logger\Logging;

    /**
     * Default Gransy/Subreg authoritative nameservers.
     *
     * @var list<string>
     */
    public const GRANSY_NS = [
        'ns.gransy.com',
        'ns2.gransy.com',
        'ns3.gransy.com',
        'ns4.gransy.com',
        'ns5.gransy.com',
    ];

    /**
     * Nameserver hostnames used by Gransy domain parking (expired / not in zone).
     *
     * @var list<string>
     */
    public const PARKING_NS_SUFFIXES = [
        'parktons.com',
    ];

    /**
     * ClientLibrary version.
     */
    public static string $libVersion = '1.5.0';

    /**
     * Object Configuration.
     */
    public array $config = [];

    /**
     * Soap Helper.
     */
    public \SoapClient $soaper;

    /**
     * Authentification.
     */
    public string $token = '';

    /**
     * Last call status code.
     *
     * @var null|array|string ok|error
     */
    public null|array|string $lastStatus;

    /**
     * Last call error Data.
     */
    public ?array $lastError = [];

    /**
     * Last call obtained Data.
     */
    public ?array $lastResult = [];

    /**
     * SubReg Client.
     *
     * @param array<string, bool|string> $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config ?: self::env2conf();

        if (\array_key_exists('location', $this->config) && \array_key_exists('uri', $this->config)) {
            $this->soaper = new \SoapClient(
                null,
                [
                    'location' => $this->config['location'],
                    'uri' => $this->config['uri'],
                ],
            );
        } else {
            throw new \InvalidArgumentException('location or uri unset');
        }

        $this->setObjectName();

        if ((\array_key_exists('autologin', $this->config) === false) || $this->config['autologin']) {
            $this->login();
        }
    }

    /**
     * Convert ENV keys to configuration.
     *
     * Provides production SOAP endpoints when SUBREG_LOCATION / SUBREG_URI
     * are unset so scripts only need login credentials.
     *
     * @return array<string, string>
     */
    public static function env2conf(): array
    {
        return [
            'location' => (string) (\Ease\Shared::cfg('SUBREG_LOCATION') ?: 'https://soap.subreg.cz/cmd.php'),
            'uri' => (string) (\Ease\Shared::cfg('SUBREG_URI') ?: 'https://soap.subreg.cz/soap'),
            'login' => (string) \Ease\Shared::cfg('SUBREG_LOGIN'),
            'password' => (string) \Ease\Shared::cfg('SUBREG_PASSWORD'),
        ];
    }

    /**
     * Format hostnames for ModifyNS_Domain / Create_Domain ns.hosts.
     *
     * @param list<string> $hostnames
     *
     * @return list<array{hostname: string}>
     */
    public static function formatNsHosts(array $hostnames): array
    {
        $ns = [];

        foreach ($hostnames as $host) {
            $ns[] = ['hostname' => rtrim((string) $host, '.')];
        }

        return $ns;
    }

    /**
     * Whether nameserver list indicates Gransy parking (expired domain).
     *
     * @param list<string>|array<int|string, mixed> $hosts
     */
    public static function hostsIndicateParking(array $hosts): bool
    {
        foreach ($hosts as $host) {
            if (\is_array($host)) {
                $host = $host['hostname'] ?? $host['name'] ?? '';
            }

            $normalized = strtolower(rtrim((string) $host, '.'));

            foreach (self::PARKING_NS_SUFFIXES as $suffix) {
                if ($normalized === $suffix || str_ends_with($normalized, '.'.$suffix)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Whether account credit is at/above the autorenew threshold.
     *
     * Empirically Subreg skips autorenew when amount < threshold even if
     * amount would still cover a single Renew_Domain price.
     *
     * @param array<string, mixed> $creditResponse Get_Credit response or its `credit` sub-array
     */
    public static function isCreditAboveThreshold(array $creditResponse): bool
    {
        $credit = isset($creditResponse['credit']) && \is_array($creditResponse['credit'])
            ? $creditResponse['credit']
            : $creditResponse;

        $amount = (float) ($credit['amount'] ?? 0);
        $threshold = (float) ($credit['threshold'] ?? 0);

        return $amount >= $threshold;
    }

    /**
     * Add Info about used user, server and libraries.
     *
     * @param string $additions Additional note text
     *
     * @return bool was logged ?
     */
    public function logBanner($additions = null)
    {
        return $this->addStatusMessage(
            'API '.str_replace(
                '://',
                '://'.$this->config['login'].'@',
                $this->config['uri'],
            ).' php-subreg v'.self::$libVersion.' '.$additions,
            'debug',
        );
    }

    /**
     * API Call.
     *
     * @param string                                                $command command to execute
     * @param array<string, array<string, float|int|string>|string> $params  command parameters
     *
     * @throws ApiException when Subreg returns status=error
     *
     * @return array<string, array<string, float|int|string>|string> result
     */
    public function call(string $command, array $params = [])
    {
        $this->lastError = null;
        $this->lastStatus = null;
        $this->lastResult = null;

        if ($this->token && !\array_key_exists('ssid', $params)) {
            $params['ssid'] = $this->token;
        }

        $responseRaw = $this->soaper->__soapCall($command, ['data' => $params]);

        if (isset($responseRaw['status'])) {
            $this->lastStatus = $responseRaw['status'];

            switch ($responseRaw['status']) {
                case 'ok':
                    if (\array_key_exists('data', $responseRaw)) {
                        $this->lastResult = $responseRaw['data'];
                    } else {
                        $this->lastResult = $responseRaw;
                    }

                    break;
                case 'error':
                    $this->logError($responseRaw['error']);
                    $this->lastResult = ['error' => $responseRaw['error']];

                    throw new ApiException($responseRaw['error']);
            }
        }

        return $this->lastResult;
    }

    /**
     * log Error.
     *
     * @param array<string, mixed> $errorData
     */
    public function logError(array $errorData): void
    {
        $this->lastError = $errorData;
        $major = $errorData['errorcode']['major'] ?? '?';
        $minor = $errorData['errorcode']['minor'] ?? '?';
        $msg = $errorData['errormsg'] ?? 'unknown error';
        $this->addStatusMessage($major.' '.$minor.': '.$msg, 'error');
    }

    /**
     * Perform Login to Server.
     *
     * @return bool success
     */
    public function login()
    {
        $result = false;
        $params = [
            'login' => $this->config['login'],
            'password' => $this->config['password'],
        ];
        $loginResponse = $this->call('Login', $params);

        if (\array_key_exists('ssid', $loginResponse)) {
            $this->token = $loginResponse['ssid'];
            $result = true;
            $this->setObjectName($params['login'].'@'.$this->getObjectName());
        }

        return $result;
    }

    /**
     * Check if domain is available or not.
     *
     * @see https://subreg.cz/manual/?cmd=Check_Domain Command: Check_Domain
     *
     * @return array<string, array<string, float|int|string>|string>
     */
    public function checkDomain(string $domain)
    {
        return $this->call('Check_Domain', ['domain' => $domain]);
    }

    /**
     * Domain details (contacts, NS hosts, exDate, autorenew, RGP, …).
     *
     * @see https://subreg.cz/manual/?cmd=Info_Domain
     *
     * @return array<string, mixed>
     */
    public function infoDomain(string $domain): array
    {
        return $this->call('Info_Domain', ['domain' => $domain]);
    }

    /**
     * Order details. Note: API parameter name is `order`, not `orderid`.
     *
     * @see https://subreg.cz/manual/?cmd=Info_Order
     *
     * @return array<string, mixed>
     */
    public function infoOrder(int|string $orderId): array
    {
        return $this->call('Info_Order', ['order' => $orderId]);
    }

    /**
     * Account credit balance and autorenew threshold.
     *
     * @see https://subreg.cz/manual/?cmd=Get_Credit
     *
     * @return array{credit: array{amount: string, reserved: string, threshold: string, currency: string, users?: string}}
     */
    public function getCredit(): array
    {
        return $this->call('Get_Credit');
    }

    /**
     * True when credit amount is at/above the configured threshold
     * (the condition Subreg uses for autorenew).
     */
    public function canAutorenew(): bool
    {
        return self::isCreditAboveThreshold($this->getCredit());
    }

    /**
     * True when Info_Domain hosts point at Gransy parking NS.
     */
    public function isDomainParked(string $domain): bool
    {
        $info = $this->infoDomain($domain);
        $hosts = $info['hosts'] ?? [];

        return \is_array($hosts) && self::hostsIndicateParking($hosts);
    }

    /**
     * Create a new domain.
     *
     * @see https://subreg.cz/manual/?cmd=Create_Domain Order: Create_Domain
     *
     * @param string        $domain
     * @param string        $registrantID
     * @param string        $contactsAdminID
     * @param string        $contactsTechID
     * @param string        $authID
     * @param array<string> $nsHosts         Hostnames of nameservers: ['ns.domain.cz','ns2.domain.cz']
     * @param string        $nsset           Nameserver Set (only for FRED registries (.CZ,.EE,...))
     * @param int           $period
     *
     * @return array
     */
    public function registerDomain(
        $domain,
        $registrantID,
        $contactsAdminID,
        $contactsTechID,
        $authID,
        $nsHosts = [],
        $nsset = null,
        $period = 1,
    ) {
        $ns = self::formatNsHosts($nsHosts);

        $order = [
            'domain' => $domain,
            'type' => 'Create_Domain',
            'params' => [
                'registrant' => [
                    'id' => $registrantID,
                ],
                'contacts' => [
                    'admin' => [
                        'id' => $contactsAdminID,
                    ],
                    'tech' => [
                        'id' => $contactsTechID,
                    ],
                ],
                'ns' => [
                    'hosts' => $ns,
                ],
                'params' => [
                    'authid' => $authID,
                ],
                'period' => $period,
            ],
        ];

        if (!empty($nsset)) {
            $order['params']['ns']['nsset'] = $nsset;
        }

        return $this->call('Make_Order', ['order' => $order]);
    }

    /**
     * Change domain nameservers.
     *
     * @see https://subreg.cz/manual/?cmd=ModifyNS_Domain
     *
     * @param list<string> $hostnames e.g. self::GRANSY_NS
     *
     * @return array<string, mixed>
     */
    public function modifyNs(string $domain, array $hostnames): array
    {
        return $this->call('Make_Order', [
            'order' => [
                'domain' => $domain,
                'type' => 'ModifyNS_Domain',
                'params' => [
                    'ns' => [
                        'hosts' => self::formatNsHosts($hostnames),
                    ],
                ],
            ],
        ]);
    }

    /**
     * Restore Gransy/Subreg default nameservers after parking.
     *
     * @return array<string, mixed>
     */
    public function restoreGransyNs(string $domain): array
    {
        return $this->modifyNs($domain, self::GRANSY_NS);
    }

    /**
     * Get all domains from your account.
     *
     * @see https://subreg.cz/manual/?cmd=Domains_List Command: Domains_List
     *
     * @return array<int, mixed>
     */
    public function domainsList(): array
    {
        return $this->call('Domains_List');
    }

    /**
     * Get pricelist from your account.
     *
     * @see https://subreg.cz/manual/?cmd=Pricelist Command: Pricelist
     */
    public function pricelist(): array
    {
        return $this->call('Pricelist');
    }

    /**
     * Get specified pricelist from your account.
     *
     * @see https://subreg.cz/manual/?cmd=Get_Pricelist Command: Get_Pricelist
     *
     * @param string $pricelist requested pricelist name
     *
     * @return array
     */
    public function getPricelist($pricelist)
    {
        return $this->call('Get_Pricelist', ['pricelist' => $pricelist]);
    }

    /**
     * Renew a domain.
     *
     * When $includeCurExpDate is true, fetches Info_Domain immediately before
     * the order and sends the current exDate (avoids stale curExpDate errors
     * during the post-expiry renewPeriod).
     *
     * @see https://subreg.cz/manual/?cmd=Renew_Domain Command: Renew_Domain
     *
     * @return array<string, mixed>
     */
    public function renewDomain(string $domain, int $years = 1, bool $includeCurExpDate = false): array
    {
        $params = ['period' => $years];

        if ($includeCurExpDate) {
            $info = $this->infoDomain($domain);
            $params['curExpDate'] = $info['exDate'];
        }

        return $this->call('Make_Order', [
            'order' => [
                'domain' => $domain,
                'type' => 'Renew_Domain',
                'params' => $params,
            ],
        ]);
    }

    /**
     * Credit_Correction.
     *
     * Correct credit amount of your sub-users. The amount you specify in this
     * command will be added to current amount. Use negative values for
     * subtracting credit. Please note that currency will depend on current
     * user setting.
     *
     * @see https://subreg.cz/manual/?cmd=Credit_Correction
     *
     * @param string $username Credit Holder Username
     * @param int    $amount   10 or -2
     * @param string $reason   For example "Invoice settle"
     */
    public function creditCorrection($username, $amount, $reason)
    {
        return $this->call(
            'Credit_Correction',
            ['username' => $username, 'amount' => (string) $amount, 'reason' => $reason],
        );
    }

    /**
     * Retrieve single sub-user.
     *
     * @param int $id ID of the user
     */
    public function infoUser(int $id)
    {
        return $this->call('Info_User', ['id' => $id]);
    }

    /**
     * List of DNS records for specified domain.
     *
     * @see https://subreg.cz/manual/?cmd=Get_DNS_Zone
     *
     * @param string $zoneName Registered domain
     *
     * @return array
     */
    public function getDnsZone(string $zoneName)
    {
        return $this->call('Get_DNS_Zone', ['domain' => $zoneName]);
    }

    /**
     * DNS analyzer (includes in_zone / dnssec flags).
     *
     * @see https://subreg.cz/manual/?cmd=Get_DNS_Info
     *
     * @return array<string, mixed>
     */
    public function getDnsInfo(string $domain): array
    {
        return $this->call('Get_DNS_Info', ['domain' => $domain]);
    }

    /**
     * Add a single DNS record.
     *
     * @see https://subreg.cz/manual/?cmd=Add_DNS_Record
     *
     * @param array{name: string, type: string, content: string, prio?: int, ttl?: int} $record
     *
     * @return array<string, mixed>
     */
    public function addDnsRecord(string $domain, array $record): array
    {
        return $this->call('Add_DNS_Record', [
            'domain' => $domain,
            'record' => $record,
        ]);
    }

    /**
     * Modify an existing DNS record (must include record id).
     *
     * @see https://subreg.cz/manual/?cmd=Modify_DNS_Record
     *
     * @param array{id: int|string, type: string, content: string, prio?: int, ttl?: int, name?: string} $record
     *
     * @return array<string, mixed>
     */
    public function modifyDnsRecord(string $domain, array $record): array
    {
        return $this->call('Modify_DNS_Record', [
            'domain' => $domain,
            'record' => $record,
        ]);
    }

    /**
     * Delete a DNS record by id.
     *
     * @see https://subreg.cz/manual/?cmd=Delete_DNS_Record
     *
     * @return array<string, mixed>
     */
    public function deleteDnsRecord(string $domain, int|string $recordId): array
    {
        return $this->call('Delete_DNS_Record', [
            'domain' => $domain,
            'record' => ['id' => $recordId],
        ]);
    }

    /**
     * Create a new order (Create_Domain, ModifyNS_Domain, Renew_Domain, …).
     *
     * @see https://subreg.cz/manual/?cmd=Make_Order
     *
     * @param array<string, mixed> $params order params (period, ns, curExpDate, …)
     *
     * @return array<string, mixed>
     */
    public function makeOrder(string $domain, string $type, array $params = []): array
    {
        return $this->call('Make_Order', [
            'order' => [
                'domain' => $domain,
                'type' => $type,
                'params' => $params,
            ],
        ]);
    }
}
