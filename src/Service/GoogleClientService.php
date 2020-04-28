<?php

namespace App\Service;

use Exception;
use Google_Client;
use Google_Exception;

/**
 * Provides the method getClient that returns a Google Client.
 * The provided constructor parameter $googleAuthConfig must contain the JSON encoded credentials of a service account.
 * It must contain at least the following keys:
 *   - type
 *   - client_id
 *   - client_email
 *   - private_key
 *
 * @package App\Service
 */
class GoogleClientService
{
    /** @var string $applicationName */
    protected $applicationName;

    /** @var array $authConfig */
    protected $authConfig;

    /**
     * Initiate the service
     *
     * @param string $applicationName
     * @param string $googleAuthConfig
     * @throws Exception
     */
    public function __construct($applicationName, $googleAuthConfig)
    {
        $this->applicationName = $applicationName;
        if (!is_string($googleAuthConfig)) {
            throw new Exception('expected $googleAuthConfig to be a string with JSON encoded credentials');
        }
        try {
            $this->authConfig = json_decode($googleAuthConfig, true);
        } catch (Exception $e) {
            throw new Exception('Could not decode $googleAuthConfig: ' . $e->getMessage());
        }
    }

    /**
     * Get the new google api client
     *
     * @param string $type
     * @return Google_Client
     * @throws Google_Exception
     */
    public function getClient($type = 'offline')
    {
        $client = new Google_Client();
        $client->setApplicationName($this->applicationName);
        $client->setAuthConfig($this->authConfig);
        $client->setAccessType($type);

        return $client;
    }
}
