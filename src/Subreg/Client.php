<?php
/**
 * Subreg - Client Class
 *
 * @author     Vítězslav Dvořák <info@vitexsoftware.cz>
 * @copyright  (C) 2018-2019 Spoje.Net
 */

namespace Subreg;

/**
 * Basic Soap Client class
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class Client extends \Ease\Molecule
{
    /**
     * ClientLibrary version
     * @var string 
     */
    static public $libVersion = '0.3';

    /**
     * Object Configuration
     * @var array
     */
    public $config = [];

    /**
     * Soap Helper
     * @var \SoapClient
     */
    public $soaper = null;

    /**
     * Authentification
     * @var string 
     */
    public $token = null;

    /**
     * Last call status code
     * @var string ok|error
     */
    public $lastStatus = null;

    /**
     * Last call error Data
     * @var array 
     */
    public $lastError = [];

    /**
     * Last call obtained Data
     * @var array 
     */
    public $lastResult = [];

    /**
     * 
     * @param type $config
     */
    public function __construct($config)
    {
        parent::__construct();
        $this->config = $config;
        $this->soaper = new \SoapClient(null,
            [
            "location" => $config['location'],
            "uri" => $config['uri']
            ]
        );
        $this->login();
    }

    /**
     * Add Info about used user, server and libraries
     *
     * @param string $additions Additional note text
     * 
     * @return boolean was logged ?
     */
    public function logBanner($additions = null)
    {
        return $this->addStatusMessage('API '.str_replace('://',
                    '://'.$this->config['login'].'@', $this->config['uri']).' php-subreg v'.self::$libVersion.' EasePHP Framework v'.\Ease\Atom::$frameworkVersion.' '.$additions,
                'debug');
    }

    /**
     * API Call
     * 
     * @param string $command command to execute
     * @param array  $params  command parameters
     * 
     * @return array
     */
    public function call(string $command, array $params = [])
    {
        $this->lastError  = null;
        $this->lastStatus = null;
        $this->lastResult = null;
        if ($this->token && !array_key_exists('ssid', $params)) {
            $params['ssid'] = $this->token;
        }
        $responseRaw = $this->soaper->__call($command, ['data' => $params]);


        if (isset($responseRaw['status'])) {
            $this->lastStatus = $responseRaw['status'];
            switch ($responseRaw['status']) {
                case 'ok':
                    if (array_key_exists('data', $responseRaw)) {
                        $this->lastResult = $responseRaw['data'];
                    } else {
                        $this->lastResult = $this->lastStatus;
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
     * log Error
     * 
     * @param array $errorData
     */
    public function logError($errorData)
    {
        $this->lastError = $errorData;
        $this->addStatusMessage($errorData['errorcode']['major'].' '.$errorData['errorcode']['minor'].': '.$errorData['errormsg'],
            'error');
    }

    /**
     * Perform Login to Server
     * 
     * @return boolean success
     */
    public function login()
    {
        $result        = false;
        $params        = [
            "login" => $this->config['login'],
            "password" => $this->config['password']
        ];
        $loginResponse = $this->call("Login", $params);
        if (array_key_exists('ssid', $loginResponse)) {
            $this->token = $loginResponse['ssid'];
            $result      = true;
        }
        return $result;
    }

    /**
     *  Check if domain is available or not
     * 
     * @link https://subreg.cz/manual/?cmd=Check_Domain Command: Check_Domain
     * 
     * @param string $domain
     * 
     * @return array
     */
    public function checkDomain($domain)
    {
        return $this->call('Check_Domain', ['domain' => $domain]);
    }

    /**
     * Create a new domain
     * 
     * @link https://subreg.cz/manual/?cmd=Create_Domain Order: Create_Domain
     * 
     * @param string $domain
     * @param string $registrantID
     * @param string $contactsAdminID
     * @param string $contactsTechID
     * @param string $authID
     * @param array  $nsHosts          Hostnames of nameservers: ['ns.domain.cz','ns2.domain.cz']
     * @param string $nsset            Nameserver Set (only for FRED registries (.CZ,.EE,...))
     * @param int    $period            
     * 
     * @return array
     */
    public function registerDomain($domain, $registrantID, $contactsAdminID,
                                   $contactsTechID, $authID, $nsHosts = [],
                                   $nsset = null, $period = 1)
    {

        foreach ($nsHosts as $host) {
            $ns[]["hostname"] = $host;
        }

        $order = array(
            "domain" => $domain,
            "type" => "Create_Domain",
            "params" => array(
                "registrant" => array(
                    "id" => $registrantID,
                ),
                "contacts" => array(
                    "admin" => array(
                        "id" => $contactsAdminID,
                    ),
                    "tech" => array(
                        "id" => $contactsTechID,
                    ),
                ),
                "ns" => array(
                    "hosts" => $ns,
                ),
                "params" => array(
                    "authid" => $authID,
                ),
                "period" => $period
            )
        );

        if (!empty($nsset)) {
            $order['params']['ns']['nsset'] = $nsset;
        }

        return $this->call('Make_Order', ['order' => $order]);
    }

    /**
     *  Get all domains from your account
     * 
     * @link https://subreg.cz/manual/?cmd=Domains_List Command: Domains_List
     * 
     * @return array
     */
    public function domainsList()
    {
        return $this->call('Domains_List');
    }

    /**
     * Get pricelist from your account
     * 
     * @link https://subreg.cz/manual/?cmd=Pricelist Command: Pricelist
     * 
     * @return array pricelist details
     */
    public function pricelist()
    {
        return $this->call('Pricelist');
    }

    /**
     * Renew a existing domain from your account
     * 
     * @link https://subreg.cz/manual/?cmd=Renew_Domain Command: Renew_Domain
     * 
     * @param string $domain name
     * @param int $years
     * 
     * @return string|array OK or Result array
     */
    public function renewDomain(string $domain, int $years = 1)
    {
        return $this->call('Make_Order',
                ['order' => ['domain' => $domain, 'params' => ['period' => $years],
                    'type' => 'Renew_Domain']]);
    }

    /**
     * Set autorenew policy for your domain. 
     * 
     * @link https://subreg.cz/manual/?cmd=Set_Autorenew Command: Set_Autorenew
     * 
     * @param string $domain
     * @param string $renew  only EXPIRE, AUTORENEW or RENEWONCE
     * 
     * @return string|array OK or Result array
     */
    public function setAutorenew(string $domain, string $renew)
    {
        return $this->call('Set_Autorenew',
                ['domain' => $domain, 'autorenew' => $renew]);
    }
}
