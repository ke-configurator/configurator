<?php

namespace App\Service;

use Google_Service_Drive;

class GoogleDriveService
{
    /** @var GoogleClientService $clientService */
    protected $clientService;

    /** @var Google_Service_Drive $driveService */
    private $driveService;

    /**
     * Initiate the service
     *
     * @param GoogleClientService $clientService
     */
    public function __construct(GoogleClientService $clientService)
    {
        //$this->clientService = $clientService;

        $client = $clientService->getClient('offline');
        $client->setScopes(implode(' ', [
            Google_Service_Drive::DRIVE
        ]));

        $this->driveService = new Google_Service_Drive($client);
    }

    public function getSpreadSheetList()
    {
        $spreadSheets = [];
        $options      = [
            'pageSize' => 10,
            'q'        => "mimeType='application/vnd.google-apps.spreadsheet'"
        ];
        foreach ($this->driveService->files->listFiles($options) as $result) {
            $spreadSheets[$result->id] = $result->name;
        }

        return $spreadSheets;
    }
}