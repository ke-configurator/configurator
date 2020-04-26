<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\CalculationGroupRepository;

class MenuController extends AbstractController
{
    /**
     * @Route("/menu", name="menu")
     * @param CalculationGroupRepository $repository
     * @return Response
     */
    public function menuAction(CalculationGroupRepository $repository)
    {
        return $this->render('frontend/_menu.html.twig', [
            'calculationGroups' => $repository->findBy(['isActive' => true])
        ]);
    }
}
