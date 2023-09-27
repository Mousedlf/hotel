<?php

namespace App\Controller;

use App\Repository\ChoseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ChoseRepository $choseRepository): Response
    {
        $choses = $choseRepository->findAll();

        return $this->render('home/index.html.twig', [
            'choses' => $choses,
        ]);
    }
}
