<?php

namespace App\Service;

use App\Exception\MissingInputSheetException;
use Exception;
use Google_Exception;
use Google_Service_Sheets;
use Google_Service_Sheets_Sheet;
use Google_Service_Sheets_ValueRange;
use Google_Service_Sheets_BatchUpdateSpreadsheetRequest;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

use App\Service\Requests\GoogleSheetsRequests;

/**
 * Class GoogleSheetsService
 * @package App\Service
 */
class GoogleSheetsService extends GoogleSheetsRequests
{
    /**
     * Client service
     *
     * @var GoogleClientService
     */
    protected $clientService;

    /**
     * Google Service Sheets
     *
     * @var Google_Service_Sheets
     */
    protected $sheetService;

    /**
     * Goggle Spreadsheets id
     *
     * @var string
     */
    protected $id;

    /** @var array $sheets */
    protected $sheets;

    /**
     * Initiate the service
     *
     * @param GoogleClientService $clientService
     */
    public function __construct(GoogleClientService $clientService)
    {
        $this->clientService = $clientService;
        $this->sheets        = [];
    }

    /**
     * Set google spi client service.
     * Set google spreadsheets id to be used.
     *
     * @param string $id
     * @return boolean
     */
    public function setSheetServices($id)
    {
        if (empty($id)) {
            throw new InvalidConfigurationException("spreadsheets id can not be empty");
        }

        $this->id = $id;
        $client   = $this->clientService->getClient('offline');   // get api clirent
        $client->setScopes(implode(' ', [
            Google_Service_Sheets::DRIVE,
            Google_Service_Sheets::SPREADSHEETS
        ]));

        $this->sheetService = new Google_Service_Sheets($client);

        return true;
    }

    public function getService()
    {
        return $this->sheetService;
    }

    /**
     * Get a existing google spreadsheets
     * Return an array of error messages for an error.
     *
     * @return mixed(Google_Service_Sheets|array)
     */
    public function getGoogleSpreadSheets()
    {
        try {
            return $this->sheetService->spreadsheets->get($this->id);
        } catch (Exception $ex) {
            return json_decode($ex->getMessage());
        }
    }

    /**
     * @throws MissingInputSheetException
     */
    public function getInputParameters()
    {
        $inputSheetName = 'Input';
        /** @var Google_Service_Sheets_Sheet $sheet */
        foreach ($this->sheetService->spreadsheets->get($this->id) as $sheet) {
            $title                = $sheet->getProperties()->getTitle();
            $this->sheets[$title] = $this->sheetService->spreadsheets_values->get($this->id, $title, [
                'valueRenderOption' => 'FORMULA'
            ]);
        }
        if (!isset($this->sheets[$inputSheetName])) {
            throw new MissingInputSheetException($this->id);
        }

        $inputValues = $this->sheetService->spreadsheets_values->get($this->id, $inputSheetName, [
            'valueRenderOption' => 'FORMULA'
        ]);

        return $this->extractInputParameters($inputValues);
    }

    /**
     * @param Google_Service_Sheets_ValueRange $inputValues
     * @return array
     */
    protected function extractInputParameters(Google_Service_Sheets_ValueRange $inputValues): array
    {
        $parameters = [];
        foreach ($inputValues as $idx => $row) {
            if (0 === $idx) {
                continue;
            }
            $parameters[] = [
                'name'       => isset($row[0]) ? $row[0] : null,
                'label'      => isset($row[1]) ? $row[1] : null,
                'inputField' => isset($row[2]) ? $row[2] : null,
                'unit'       => isset($row[3]) ? $row[3] : null,
                'precision'  => isset($row[4]) ? $row[4] : null,
                'value'      => isset($row[2]) ? $this->getInputValue($row[2]) : null
            ];
        }

        return $parameters;
    }

    protected function getInputValue($inputField)
    {
        preg_match('/=(.*)!([A-Z])([0-9]+)/', $inputField, $matches);

        if(4 !== count($matches)) {
            return null;
        }
        $sheet  = $matches[1];
        $sheet  = $this->sheets[$sheet];
        $colIdx = $matches[2];
        $rowIdx = $matches[3];

        $value = $sheet[$rowIdx - 1][$this->alpha2num($colIdx) - 1];

        return $value;
    }

    protected function alpha2num($alpha)
    {
        $num = 0;
        foreach (str_split($alpha) as $char) {
            $num = $num * 10 + ord($char) - 64;
        }

        return $num;
    }

    /**
     * Create the new sheet.
     *
     * @param string $title
     * @param array $data
     * @param int $header
     * @return mixed(int|boolean)
     */
    public function createNewSheet($title = '', $data = [], $header = 0)
    {
        if (empty($title)) {
            throw new InvalidConfigurationException("Sheet title can not be empty");
        }

        $addNewSheetResponse = $this->addNewSheet($title, $data);
        if ($addNewSheetResponse) {
            return $this->insertDataForNewSheet($title, $data, $header);
        }

        return false;
    }

    /**
     * Add the new spreadsheets sheet
     *
     * @param string $title
     * @param array $data
     * @return boolean
     */
    public function addNewSheet($title, $data = [])
    {
        if (empty($data)) {
            $gidProperties = ["rowCount" => 1000, "columnCount" => 26];

            return $this->addNewSheetWithoutData($title, $gidProperties);
        }

        return $this->addNewSheetWithData($title);
    }

    /**
     * Add the new sheet in the spreadsheets with minimum grid size
     * The grid size will be extended with data
     *
     * @param string $title
     * @return boolean
     */
    public function addNewSheetWithData($title)
    {
        try {
            $request     = $this->getNewSheetRequest($title);
            $requestBody = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest();
            $requestBody->setRequests($request);
            $this->sheetService->spreadsheets->batchUpdate($this->id, $requestBody);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * Add the new sheet in the spreadsheets with the default grid size(26x1000)
     *
     * @param string $title
     * @param null $gidProperties
     * @return boolean
     */
    public function addNewSheetWithoutData($title, $gidProperties = null)
    {
        try {
            $request     = $this->getNewSheetRequest($title, 'GRID', $gidProperties);
            $requestBody = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest();
            $requestBody->setRequests($request);
            $this->sheetService->spreadsheets->batchUpdate($this->id, $requestBody);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * Insert data to the new sheet
     *
     * @param string $title
     * @param array $data
     * @param int $header
     * @return int
     */
    public function insertDataForNewSheet($title = '', $data = [], $header = 0)
    {
        if (is_array($data) && count($data) > 0) {
            $range = $this->getSheetRangeByData($title, $data, $header);

            return $this->InsertSheetData($range, $data);
        }

        return 0;
    }

    /**
     * Get the sheet range for the given data
     * Data must be a two dimensional array
     *
     * @param string $title
     * @param array $data
     * @param int $header
     * @param string $startCol
     * @return string
     * @throws InvalidConfigurationException
     */
    public function getSheetRangeByData($title = '', $data = [], $header = 0, $startCol = 'A')
    {
        if (!is_array($data) || empty($title)) {
            throw new InvalidConfigurationException("Sheet title is missing or incorrect data format");
        }

        $startRow = $header + 1;
        $rows     = array_keys($data);
        $numCols  = $this->getNumberOfDataCols($rows, $data);
        $endCol   = $this->getEndColRange($startCol, $numCols);
        $endRow   = $startRow + (count($rows) - 1);

        return $title . '!' . $startCol . $startRow . ':' . $endCol . $endRow;
    }

    /**
     * Get the last column character by the column size automatically up to 676 columns
     *
     * @param string $startCol
     * @param int $numCols
     * @return string
     * @throws InvalidConfigurationException
     */
    public function getEndColRange($startCol = 'A', $numCols = 0)
    {
        if ($numCols > 676) {
            throw new InvalidConfigurationException("Out of range for number of columns, use InsertSheetData()");
        } elseif ($numCols > 26) {
            $repeat   = (int)floor($numCols / 26) - 1;
            $leftOver = (int)($numCols % 26) - 1;
            $endCol   = chr(ord($startCol) + $leftOver);
            $preCol   = chr(ord($startCol) + $repeat);

            return $preCol . $endCol;
        }

        return chr(ord($startCol) + ($numCols - 1));
    }

    /**
     * Get the number of data columns
     *
     * @param array $rows
     * @param array $data
     * @return int
     * @throws InvalidConfigurationException
     */
    public function getNumberOfDataCols($rows, $data)
    {
        if (isset($data[$rows[0]]) && is_array($data[$rows[0]])) {
            $cols = array_keys($data[$rows[0]]);

            return count($cols);
        }
        throw new InvalidConfigurationException("Data must be 2 dimensional array");
    }

    /**
     * Insert data grid to the sheet
     *
     * @param string $range
     * @param array $data
     * @return int
     */
    public function InsertSheetData($range, $data)
    {
        if (!empty($range) && !empty($data)) {
            $inputOption = ['valueInputOption' => 'RAW'];
            $requestBody = new Google_Service_Sheets_ValueRange();
            $requestBody->setMajorDimension('ROWS');
            $requestBody->setRange($range);
            $requestBody->setValues($data);
            $response = $this->sheetService->spreadsheets_values->update($this->id, $range, $requestBody, $inputOption);

            return $response->getUpdatedRows();
        }

        return 0;
    }

    /**
     * Clear all sheet contents by the id
     *
     * @param int $sheetId
     * @return boolean
     */
    public function clearSheetById($sheetId)
    {
        try {
            $request     = $this->getClearSheetRequest($sheetId);
            $requestBody = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest();
            $requestBody->setRequests($request);
            $this->sheetService->spreadsheets->batchUpdate($this->id, $requestBody);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * Clear all sheet contents by the title
     *
     * @param string $title
     * @return boolean
     */
    public function clearSheetByTitle($title)
    {
        $sheetId = $this->getSheetIdByTitle($title);
        if ($sheetId) {
            return $this->clearSheetById($sheetId);
        }

        return false;
    }

    /**
     * Delete the sheet by the id
     *
     * @param int $sheetId
     * @return boolean
     */
    public function deleteSheetById($sheetId)
    {
        try {
            $request     = $this->getDeleteSheetRequest($sheetId);
            $requestBody = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest();
            $requestBody->setRequests($request);
            $this->sheetService->spreadsheets->batchUpdate($this->id, $requestBody);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * Delete the sheet by the title
     *
     * @param string $title
     * @return boolean
     */
    public function deleteSheetByTitle($title)
    {
        $sheetId = $this->getSheetIdByTitle($title);
        if ($sheetId) {
            return $this->deleteSheetById($sheetId);
        }

        return false;
    }

    /**
     * Update data grid for the sheet
     *
     * @param string $title
     * @param array $data
     * @param int $header
     * @return mixed(int|boolean)
     */
    public function updateSheet($title, $data, $header)
    {
        $range = $this->getSheetRangeByData($title, $data, $header);
        if ($range) {
            return $this->InsertSheetData($range, $data);
        }

        return false;
    }

    /**
     * Get the sheet id by the title
     *
     * @param string $title
     * @return boolean
     */
    public function getSheetIdByTitle($title)
    {
        $sheets = $this->getGoogleSpreadSheets();
        foreach ($sheets as $key => $sheet) {
            if (isset($sheet->properties->title) && $sheet->properties->title == $title) {
                return $sheet->properties->sheetId;
            }
        }

        return false;
    }
}
