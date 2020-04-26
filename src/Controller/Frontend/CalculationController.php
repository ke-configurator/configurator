<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\CalculationGroupRepository;

class CalculationController extends AbstractController
{
    /**
     * @Route("/calculation", name="calculation")
     * @param CalculationGroupRepository $repository
     * @return Response
     */
    public function index(CalculationGroupRepository $repository)
    {
        return $this->render('frontend/calculation.html.twig', [
            'calculationGroups' => $repository->findAll()
        ]);
    }
}
