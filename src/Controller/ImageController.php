<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Room;
use App\Form\DropImageType;
use App\Form\ImageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/image')]
class ImageController extends AbstractController
{
    #[Route('/{id}', name: 'app_image')]
    public function index(Room $room): Response
    {
        //$image = new Image();
        //$formImage= $this->createForm(ImageType::class, $image);
        $formImage = $this->createForm(DropImageType::class);


               $truc = new Image();
        $truc = new Image();
        $truc = new Image();
 return $this->render('image/index.html.twig', [
            'room' => $room,
            'formImage' => $formImage,
        ]);
    }

    #[Route('/add/{id}', name: 'add_image')]
    public function add(Room $room, EntityManagerInterface $manager, Request $request): Response
    {
       // $image = new Image();
        //$formImage= $this->createForm(ImageType::class, $image);

        $truc = $request->files->get('drop_image');
        //dd($truc['file']);

        // recup image de la request
        // dire que nouvel objet de entité image set
        // lala persist flush
        $image = new Image();
        $image->setImageFile($truc['file']);



        $formImage = $this->createForm(ImageType::class,$image);

            $image->setOfRoom($room);
            $manager->persist($image);
            $manager->flush();


        return $this->redirectToRoute('app_image', [
            'id' => $room->getId()
        ]);
    }

    #[Route('/remove/{id}', name: 'remove_image')]
    public function delete(EntityManagerInterface $manager, Image $image): Response
    {
        if($image){
            $manager->remove($image);
            $manager->flush();

            $room = $image->getOfRoom();
        }

        return $this->redirectToRoute('app_image', [
           'id' => $room->getId()
        ]);
    }
}
