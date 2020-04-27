<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\SpreadSheet;
use App\Exception\MissingInputSheetException;
use App\Model\InputMeta;
use App\Model\InputMetaCollection;
use App\Repository\CalculationGroupRepository;
use App\Repository\SpreadSheetRepository;
use App\Service\GoogleSheetsService;

class CalculationController extends AbstractController
{
    /**
     * @Route("/calculation", name="calculation_home")
     * @param CalculationGroupRepository $repository
     * @return Response
     */
    public function index(CalculationGroupRepository $repository)
    {
        return $this->render('frontend/calculation.html.twig', [
            'calculationGroups' => $repository->findAll()
        ]);
    }

    /**
     * @Route("/calculation/{spreadsheetId}", name="calculation_input")
     * @param SpreadSheetRepository $repository
     * @param string $spreadsheetId
     * @return Response
     * @throws MissingInputSheetException
     */
    public function input(SpreadSheetRepository $repository, string $spreadsheetId)
    {
        /** @var SpreadSheet $spreadsheet */
        $spreadsheet         = $repository->findOneBy(['uid' => $spreadsheetId]);
        $inputMetaCollection = $spreadsheet->getInputConfig();
        $form                = $this->createInputForm($spreadsheetId, $inputMetaCollection);

        return $this->render('frontend/input.html.twig', [
            'form'            => $form->createView(),
            'inputParameters' => $inputMetaCollection
        ]);
    }


    /**
     * @Route("/update/{spreadsheetId}", name="update")
     * @param string $spreadsheetId
     * @param Request $request
     * @param SpreadSheetRepository $repository
     * @param GoogleSheetsService $service
     * @return Response
     * @throws MissingInputSheetException
     */
    public function update(
        string $spreadsheetId,
        Request $request,
        SpreadSheetRepository $repository,
        GoogleSheetsService $service
    ) {
        /** @var SpreadSheet $spreadsheet */
        $spreadsheet         = $repository->findOneBy(['uid' => $spreadsheetId]);
        $inputMetaCollection = $spreadsheet->getInputConfig();

        $output = [];
        $form   = $this->createInputForm($spreadsheetId, $inputMetaCollection);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {

            $service->setSheetServices($spreadsheetId);
            foreach ($form->getData() as $variableName => $value) {

                $inputMeta = $inputMetaCollection->findMetaByVariableName($variableName);
                $range     = preg_replace('/^=+/', '', $inputMeta->getReference());
                $service->InsertSheetData($range, [[$value]]);
            }

            $output = $service->getOutput();
        }

        return $this->render('frontend/input.html.twig', [
            'form'            => $form->createView(),
            'inputParameters' => $inputMetaCollection,
            'output'          => $output
        ]);
    }

    /**
     * @param string $spreadsheetId
     * @param InputMetaCollection $inputMetaCollection
     * @return FormInterface
     */
    protected function createInputForm(string $spreadsheetId, InputMetaCollection $inputMetaCollection)
    {
        $form = $this->createFormBuilder();

        /** @var InputMeta $inputMeta */
        foreach ($inputMetaCollection as $inputMeta) {
            $label = $inputMeta->getLabel();
            if ($inputMeta->getUnit()) {
                $label .= ('[' . $inputMeta->getUnit() . ']');
            }
            $form->add($inputMeta->getVariable(), TextType::class, [
                'label' => $label,
                'data'  => $inputMeta->getDefault()
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

}
