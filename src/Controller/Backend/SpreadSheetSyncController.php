<?php

namespace App\Controller\Backend;

use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\SpreadSheet;
use App\Repository\SpreadSheetRepository;
use App\Service\DriveService;

class SpreadSheetSyncController extends AbstractController
{
    /**
     * @Route("/syncSpreadSheets", name="sync_spreadsheets")
     * @param SpreadSheetRepository $repository
     * @param DriveService $driveService
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     * @throws ConnectionException
     */
    public function syncSpreadSheets(
        SpreadSheetRepository $repository,
        DriveService $driveService,
        EntityManagerInterface $em
    ) {
        $em->getConnection()->beginTransaction();
        try {
            $existingSpreadSheets = [];
            /** @var SpreadSheet $spreadSheet */
            foreach ($repository->findAll() as $spreadSheet) {
                $existingSpreadSheets[$spreadSheet->getUid()] = $spreadSheet;
            }

            foreach ($driveService->getSpreadSheetList() as $name => $uid) {
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
                $spreadSheet->setStatus(SpreadSheet::STATUS_SYNCED);
            }

            foreach ($existingSpreadSheets as $spreadSheet) {
                $spreadSheet->setStatus(SpreadSheet::STATUS_MISSING);
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