<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Room;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('{_locale}/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation')]
    public function index(ReservationRepository $repository): Response
    {


        return $this->render('reservation/index.html.twig');
    }

    #[Route('/{id}', name: 'reservation_search_parameters')]
    public function search(Request $request, Room $room, EntityManagerInterface $manager): Response
    {
        $reservation = new Reservation();
        $formReservation = $this->createForm(ReservationType::class, $reservation);
        $formReservation->handleRequest($request);
        if($formReservation->isSubmitted() && $formReservation->isSubmitted()){

            $checkIn= $reservation->getCheckIn();
            $checkOut=$reservation->getCheckOut();
            $totalNights = $checkIn->diff($checkOut)->days;

            $costPerNight= $room->getPrice();
            $totalCost= $totalNights*$costPerNight;
            $reservation->setCost($totalCost);

            $reservation->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($reservation);
            $manager->flush();

            return $this->render('reservation/guest.html.twig');


        }

        return $this->render('reservation/add.html.twig', [
            'formReservation' => $formReservation,
            'room'=>$room
        ]);
    }

    #[Route('/{id}/guest', name: 'reservation_guest_info')]
    public function guestInfo(Request $request, Room $room): Response
    {
        $reservation = new Reservation();
        $formReservation = $this->createForm(ReservationType::class, $reservation);
        $formReservation->handleRequest($request);

        return $this->render('reservation/add.html.twig', [
            'formReservation' => $formReservation,
            'room'=>$room
        ]);
    }
}
