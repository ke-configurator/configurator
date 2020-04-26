<?php

namespace App\Service;

use Exception;
use Google_Client;
use Google_Exception;
use Google_Service_Sheets;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 * GoogleClientService Class
 *
 * @package App\Service
 */
class GoogleClientService
{

    /**
     * Application name
     *
     * @var string
     */
    protected $applicationName;

    /**
     * Credential location
     *
     * @var string
     */
    protected $credentials;

    /**
     * Initiate the service
     *
     * @param string $applicationName
     * @param string $credentials
     */
    public function __construct($applicationName, $credentials)
    {
        $this->applicationName = $applicationName;
        putenv("GOOGLE_APPLICATION_CREDENTIALS=$credentials");
    }

    /**
     * Get the new google api client
     *
     * @param string $type
     * @return Google_Client
     */
    public function getClient($type = 'offline')
    {
        $client = new Google_Client();
        $client->setApplicationName($this->applicationName);
        $client->useApplicationDefaultCredentials();
        $client->setAccessType($type);

        return $client;
    }
}
