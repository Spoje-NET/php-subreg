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
     * ClientLibrary version.
     */
    public static string $libVersion = '1.2.0';

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
    public $lastStatus;

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
     * @param array<string, string> $config
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
        $this->login();
    }

    /**
     * Convert ENV keys to configuration.
     *
     * @return array<string, string>
     */
    public static function env2conf(): array
    {
        $conf['location'] = \Ease\Shared::cfg('SUBREG_LOCATION');
        $conf['uri'] = \Ease\Shared::cfg('SUBREG_URI');
        $conf['login'] = \Ease\Shared::cfg('SUBREG_LOGIN');
        $conf['password'] = \Ease\Shared::cfg('SUBREG_PASSWORD');

        return $conf;
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
     * @return array
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

                    break;
            }
        }

        return $this->lastResult;
    }

    /**
     * log Error.
     *
     * @param array<int, array<int, string>> $errorData
     */
    public function logError(array $errorData): void
    {
        $this->lastError = $errorData;
        $this->addStatusMessage(
            $errorData['errorcode']['major'].' '.$errorData['errorcode']['minor'].': '.$errorData['errormsg'],
            'error',
        );
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
     *  Check if domain is available or not.
     *
     * @see https://subreg.cz/manual/?cmd=Check_Domain Command: Check_Domain
     *
     * @return array
     */
    public function checkDomain(string $domain)
    {
        return $this->call('Check_Domain', ['domain' => $domain]);
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
        $period = 1
    ) {
        foreach ($nsHosts as $host) {
            $ns[]['hostname'] = $host;
        }

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
                    'hosts' => $nsHosts,
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
     *  Get all domains from your account.
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
     *  Get pricelist from your account.
     *
     * @see https://subreg.cz/manual/?cmd=Pricelist Command: Pricelist
     */
    public function pricelist(): array
    {
        return $this->call('Pricelist');
    }

    /**
     *  Get specified pricelist from your account.
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
     * @see https://subreg.cz/manual/?cmd=Renew_Domain Command: Renew_Domain
     *
     * @param string $domain name
     *
     * @return array
     */
    public function renewDomain(string $domain, int $years = 1)
    {
        return $this->call('Make_Order', ['order' => ['domain' => $domain, 'params' => ['period' => $years], 'type' => 'Renew_Domain']]);
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
     * Create a new order (CreateDomain, ModifyDomain, RenewDomain, ... ).
     *
     * @see https://subreg.cz/manual/?cmd=Make_Order
     *
     *   Create_Domain
     *   PremiumCreate_Domain
     *   Transfer_Domain
     *   PremiumTransfer_Domain
     *   AccountTransfer_Domain
     *   TransferApprove_Domain
     *   TransferDeny_Domain
     *   TransferCancel_Domain
     *   SKChangeOwner_Domain
     *   Modify_Domain
     *   ModifyNS_Domain
     *   Delete_Domain
     *   Restore_Domain
     *   PremiumRestore_Domain
     *   Renew_Domain
     *   PremiumRenew_Domain
     *   Backorder_Domain
     *   Preregister_Domain
     *   Create_Object
     *   Transfer_Object
     *   Update_Object
     *   TransferRU_Request
     *
     *   Certificate_Request
     *
     * @return array
     */
    public function makeOrder(string $domain, string $type, array $data = [])
    {
        return $this->call('Make_Order', ['domain' => $domain, 'type' => $type, 'params' => $data]);
    }
}
