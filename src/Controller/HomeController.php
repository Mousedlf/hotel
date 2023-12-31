<?php

namespace App\Controller;

use App\Entity\Establishment;
use App\Repository\EstablishmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: '')]
    public function index(EstablishmentRepository $repository): Response
    {
        $establishments = $repository->findAll();

        return $this->redirectToRoute('app_home');
    }

    #[Route('/{_locale}', name: 'app_home')]
    public function indexHome(EstablishmentRepository $repository): Response
    {
        $establishments = $repository->findAll();

        return $this->render('home/index.html.twig', [
            'establishments'=>$establishments
        ]);
    }
}
