<?php

namespace App\Command;

use Google_Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use App\Service\GoogleApiClientService;
use App\Service\GoogleSheetsApiService;

class GoogleSheetsApiCommand extends Command
{
    protected static $defaultName = 'googlesheets:execute';

    /** @var GoogleApiClientService $clientService */
    private $clientService;
    /** @var GoogleSheetsApiService $sheetsService */
    private $sheetsService;

    /**
     * @param GoogleApiClientService $clientService
     * @param GoogleSheetsApiService $sheetsService
     */
    public function __construct(GoogleApiClientService $clientService, GoogleSheetsApiService $sheetsService)
    {
        $this->clientService = $clientService;
        $this->sheetsService = $sheetsService;

        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->addOption('function', null, InputOption::VALUE_OPTIONAL, 'sheets api function to be executed')
            ->addOption('title', null, InputOption::VALUE_OPTIONAL, 'sheet title in string')
            ->addOption('id', null, InputOption::VALUE_OPTIONAL, 'spreadsheets id in integer', 0)
            ->addOption('header', null, InputOption::VALUE_OPTIONAL, 'number of rows for the header', 0)
            ->addOption('data', null, InputOption::VALUE_OPTIONAL, 'grid data in 2 dimensional array');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws Google_Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $function   = $input->getOption('function');
        $id         = $input->getOption('id');
        $sheetTitle = $input->getOption('title');
        $data       = json_decode($input->getOption('data'));
        $header     = $input->getOption('header');

        $response = 'no action has been made';
        if ($function == 'token') {
            $response = $this->clientService->createNewSheetApiAccessToken();
        } else {
            $this->sheetsService->setSheetServices($id);
        }

        if ($function == 'get') {
            $response = $this->sheetsService->getGoogleSpreadSheets();
        } elseif ($function == 'create') {
            $response = $this->sheetsService->createNewSheet($sheetTitle, $data, $header);
        } elseif ($function == 'update') {
            $response = $this->sheetsService->updateSheet($sheetTitle, $data, $header);
        } elseif ($function == 'clear') {
            $response = $this->sheetsService->clearSheetByTitle($sheetTitle);
        } elseif ($function == 'delete') {
            $response = $this->sheetsService->deleteSheetByTitle($sheetTitle);
        }

        $output->writeln($response);

        return 0;
    }
}
