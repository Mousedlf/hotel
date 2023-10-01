<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Room;
use App\Form\ImageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/image')]
class ImageController extends AbstractController
{
    #[Route('/{id}', name: 'app_image')]
    public function index(Room $room): Response
    {
        $image = new Image();
        $formImage= $this->createForm(ImageType::class, $image);


        return $this->render('image/index.html.twig', [
            'room' => $room,
            'formImage' => $formImage,
        ]);
    }

    #[Route('/manage/{id}', name: 'manage_image')]
    public function manageImages(Room $room, EntityManagerInterface $manager, Request $request): Response
    {


    }
}
