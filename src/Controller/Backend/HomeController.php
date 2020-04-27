<?php

namespace App\Controller\Backend;

use App\Exception\MissingInputSheetException;
use App\Service\GoogleApiClientService;
use App\Service\GoogleClientService;
use App\Service\GoogleDriveService;
use App\Service\GoogleSheetsService;
use Google_Exception;
use Google_Service_Drive;
use Google_Service_Sheets;
use Google_Service_Sheets_Sheet;
use Google_Service_SQLAdmin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @param string $spreadsheetId
     * @param array $inputParameters
     * @return FormInterface
     */
    protected function createInputForm(string $spreadsheetId, $inputParameters)
    {
        $form = $this->createFormBuilder();

        foreach ($inputParameters as $inputParameter) {
            $label = $inputParameter['label'];
            if ($inputParameter['unit']) {
                $label .= ('[' . $inputParameter['unit'] . ']');
            }
            $form->add($inputParameter['name'], TextType::class, [
                'label' => $label,
                'data'  => $inputParameter['value']
            ]);
        }
        $form->add('submit', SubmitType::class);
        $form
            ->setMethod('POST')
            ->setAction($this->generateUrl('update', [
                'spreadsheetId' => $spreadsheetId
            ]));

        return $form->getForm();
    }

    /**
     * @Route("/home", name="home")
     * @param GoogleApiClientService $clientService
     * @param GoogleSheetsService $service
     * @return Response
     * @throws Google_Exception
     */
    public function index(GoogleApiClientService $clientService, GoogleSheetsService $service)
    {

        $client = (new GoogleClientService())->getClient();

        $spreadsheetId = '1NwjGYEJS8JZzRseB2agyD_mxKJyjyTIswWnRSylpLsg';
        $spreadsheetId = '1Nmj-zA3O9hDAlo3-4QCiPgDeBM7Qlle8hWNSwuCaHWg';

        $service->setSheetServices($spreadsheetId);
        try {
            $data = $service->getInputParameters();
        } catch (MissingInputSheetException $e) {
            printf($e->getMessage());

        }

        new Google_Service_Sheets($client);
        $response = $service->spreadsheets_values->get($spreadsheetId, "A:E");
        $values   = $response->getValues();
//        $client = $clientService->getClient();
//        $drive = new \Google_Service_Drive($client);
//        $sheetsList = $drive->files->listFiles([
//            'q' => "mimeType='application/vnd.google-apps.spreadsheet'",
//            'fields' => 'nextPageToken, files(id, name)'
//        ]);

        foreach ($values as $value) {
            $a = $value;
        }
        $sheets = [];
        foreach ($service->spreadsheets->get($spreadsheetId) as $sheet) {

            /** @var Google_Service_Sheets_Sheet $sheet */
            $title          = $sheet->getProperties()->getTitle();
            $sheets[$title] = $service->spreadsheets_values->get($spreadsheetId, $title, [
                'valueRenderOption' => 'FORMULA'
            ]);
            $b              = $sheet;
        }
        $spreadsheetId = '19WfVnc16d4CZTiAIv75UbzO5pLeZBmLT';
        $service       = new Google_Service_Sheets($clientService->getClient());
        $response      = $service->spreadsheets_values->get($spreadsheetId, $range);


    }
}
