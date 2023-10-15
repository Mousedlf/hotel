<?php

namespace App\Controller;

use App\Entity\Establishment;
use App\Form\EstablishmentType;
use App\Repository\EstablishmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/establishment')]
class EstablishmentController extends AbstractController
{

    #[Route('/', name: 'app_establishment')]
    public function index(EstablishmentRepository $establishmentRepository): Response
    {
        $establishments = $establishmentRepository->findAll();

        return $this->render('establishment/index.html.twig', [
            'establishments' => $establishments,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit_establishment')]
    #[Route('/new', name: 'new_establishment')]
    public function addInfo(EntityManagerInterface $manager, Request $request, Establishment $establishment=null): Response
    {
        $edit = false;
        if ($establishment) {$edit = true;}
        if (!$edit) {$establishment = new Establishment();}

        $formEstablishment = $this->createForm(EstablishmentType::class, $establishment);
        $formEstablishment->handleRequest($request);
        if($formEstablishment->isSubmitted() && $formEstablishment->isSubmitted()){

            $manager->persist($establishment);
            $manager->flush();

            return $this->redirectToRoute('app_establishment');
        }


        return $this->render('establishment/edit.html.twig', [
            'formEstablishment' => $formEstablishment,
            'edit' => $edit
        ]);
    }

    #[Route('/remove/{id}', name: 'delete_establishment')]
    public function delete(Establishment $establishment, EntityManagerInterface $manager): Response
    {
        if ($establishment) {
            $manager->remove($establishment);
            $manager->flush();
        }

        return $this->redirectToRoute('app_establishment');
    }
}
