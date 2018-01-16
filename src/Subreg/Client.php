<?php
/**
 * Subreg - Usage Example
 *
 * @author     Vítězslav Dvořák <info@vitexsoftware.cz>
 * @copyright  (C) 2018 Spoje.Net
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
     * API Call
     * 
     * @param string $command command to execute
     * @param array  $params  command parameters
     * 
     * @return array
     */
    public function call($command, $params = [])
    {
        $this->lastError = null;
        $this->lastStatus = null;
        $this->lastResult = null;
        if ($this->token && !array_key_exists('ssid', $params)) {
            $params['ssid'] = $this->token;
        }
        $responseRaw = $this->soaper->__call($command, ['data' => $params]);
        if ($responseRaw['status'] == 'error') {
            
            $this->logError($responseRaw['error']);
        }
        
        $this->lastResult = array_key_exists('data', $responseRaw) ?  $responseRaw['data']  : null ; 
        
        return $this->lastResult;
    }

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
}
