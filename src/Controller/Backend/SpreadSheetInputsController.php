<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Exception\MissingInputSheetException;
use App\Repository\SpreadSheetRepository;
use App\Service\GoogleSheetsService;

class SpreadSheetInputsController extends AbstractController
{
    /**
     * @Route("/backend/admin/spreadSheet/inputs", name="inputs")
     * @param Request $request
     * @param SpreadSheetRepository $repository
     * @param GoogleSheetsService $service
     * @return Response
     * @throws MissingInputSheetException
     */
    public function inputsAction(Request $request, SpreadSheetRepository $repository, GoogleSheetsService $service)
    {
        $id          = $request->query->get('id');
        $spreadsheet = $repository->find($id);
        $service->setSheetServices($spreadsheet->getUid());
        $inputParameters = $service->getInputParameters();

        return $this->render('backend/home/input.html.twig', [
            'inputParameters' => $inputParameters
        ]);
    }
}