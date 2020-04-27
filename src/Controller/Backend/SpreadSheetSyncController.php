<?php

namespace App\Controller\Backend;

use App\Exception\MissingInputSheetException;
use App\Service\GoogleSheetsService;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\SpreadSheet;
use App\Repository\SpreadSheetRepository;
use App\Service\GoogleDriveService;

class SpreadSheetSyncController extends AbstractController
{
    /**
     * @Route("/syncSpreadSheets", name="sync_spreadsheets")
     * @param SpreadSheetRepository $repository
     * @param GoogleDriveService $driveService
     * @param GoogleSheetsService $sheetService
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function syncSpreadSheets(
        SpreadSheetRepository $repository,
        GoogleDriveService $driveService,
        GoogleSheetsService $sheetService,
        EntityManagerInterface $em
    ) {
        $em->getConnection()->beginTransaction();
        try {
            $existingSpreadSheets = [];
            /** @var SpreadSheet $spreadSheet */
            foreach ($repository->findAll() as $spreadSheet) {
                $existingSpreadSheets[$spreadSheet->getUid()] = $spreadSheet;
            }

            foreach ($driveService->getSpreadSheetList() as $uid => $name) {
                $sheetService->setSheetServices($uid);
                if (!isset($existingSpreadSheets[$uid])) {
                    $spreadSheet = new SpreadSheet();
                    $spreadSheet
                        ->setName($name)
                        ->setUid($uid);
                    $em->persist($spreadSheet);
                } else {
                    $spreadSheet = $existingSpreadSheets[$uid];
                    unset($existingSpreadSheets[$uid]);
                }
                try {
                    $inputConfig = $sheetService->getInputParameters();
                    $spreadSheet
                        ->setInputConfig($inputConfig)
                        ->setStatus(SpreadSheet::STATUS_SYNCED);
                } catch (MissingInputSheetException $e) {
                    $spreadSheet->setStatus(SpreadSheet::STATUS_MISSING_INPUT);
                }
            }

            foreach ($existingSpreadSheets as $spreadSheet) {
                $spreadSheet->setStatus(SpreadSheet::STATUS_MISSING_FILE);
            }

            $em->flush();
            $em->getConnection()->commit();
        } catch (Exception $e) {
            $em->getConnection()->rollBack();
        }

        return $this->redirectToRoute('easyadmin', [
            'action' => 'list',
            'entity' => 'SpreadSheet',
        ]);
    }
}