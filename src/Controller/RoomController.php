<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('{_locale}/room')]
class RoomController extends AbstractController
{
    #[Route('/', name: 'app_room')]
    public function index(RoomRepository $roomRepository): Response
    {
        $rooms = $roomRepository->findAll();

        return $this->render('room/index.html.twig', [
            'rooms' => $rooms,
        ]);
    }

    #[Route('/admin/', name: 'app_room_admin')]
    public function indexAdmin(RoomRepository $roomRepository): Response
    {
        $rooms = $roomRepository->findAll();

        return $this->render('room/indexAdmin.html.twig', [
            'rooms' => $rooms,
        ]);
    }

    #[Route('/{id}', name: 'show_room')]
    public function show(Room $room): Response
    {
        return $this->render('room/show.html.twig', [
            'room' => $room,
        ]);
    }

    #[Route('/admin/remove/{id}', name: 'delete_room')]
    public function delete(Room $room, EntityManagerInterface $manager): Response
    {
        if ($room) {
            $manager->remove($room);
            $manager->flush();
        }

        return $this->redirectToRoute('app_room_admin');
    }

    #[Route('/admin/edit/{id}', name: 'edit_room', priority: 2)]
    #[Route('/admin/new', name: 'new_room', priority: 2)]
    public function add(EntityManagerInterface $manager, Request $request, Room $room = null): Response
    {

        $edit = false;
        if ($room) {$edit = true;}
        if (!$edit) {$room = new Room();}

        $formRoom = $this->createForm(RoomType::class, $room);
        $formRoom->handleRequest($request);
        if ($formRoom->isSubmitted() && $formRoom->isValid()) {

            $manager->persist($room);
            $manager->flush();

            return $this->redirectToRoute('app_room_admin');
        }

        return $this->render('room/new.html.twig', [
            'formRoom' => $formRoom,
            'edit' => $edit,
        ]);

    }


}

