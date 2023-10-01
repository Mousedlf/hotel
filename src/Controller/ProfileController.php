<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'app_profile')]
    public function index(): Response
    {
        $profile= $this->getUser()->getProfile();

        return $this->render('profile/index.html.twig', [
            'profile' => $profile,
        ]);
    }

    #[Route('/edit', name: 'edit_profile')]
    public function edit(Request $request, EntityManagerInterface $manager): Response
    {
        $profile = $this->getUser()->getProfile();
        $formEditProfile = $this->createForm(ProfileType::class, $profile);
        $formEditProfile->handleRequest($request);
        if($formEditProfile->isSubmitted() && $formEditProfile->isValid()){

            $manager->persist($profile);
            $manager->flush();

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/edit.html.twig', [
            'formEditProfile'=>$formEditProfile
        ]);
    }
}
