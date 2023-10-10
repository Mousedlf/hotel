<?php

namespace App\Controller;

use App\Entity\Guest;
use App\Entity\Reservation;
use App\Entity\Room;
use App\Form\GuestInfoType;
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
    #[Route('/{id}', name: 'reservation_choices')]
    public function choices(Request $request, Room $room, EntityManagerInterface $manager): Response
    {
        $reservation = new Reservation();
        $formReservation = $this->createForm(ReservationType::class, $reservation);
        $formReservation->handleRequest($request);
        if($formReservation->isSubmitted() && $formReservation->isSubmitted()){

            $reservation->setRoom($room);
            $reservation->setCreatedAt(new \DateTimeImmutable());


            $checkIn= $reservation->getCheckIn();
            $checkOut=$reservation->getCheckOut();
            $totalNights = $checkIn->diff($checkOut)->days;

            $costPerNight= $room->getPrice();
            $totalCost= $totalNights*$costPerNight;
            $reservation->setCost($totalCost);



            $manager->persist($reservation);
            $manager->flush();

            $this->addFlash(
                'notice',
                'lala reservation possible'
            );

        }

        return $this->render('reservation/choices.html.twig', [
            'formReservation' => $formReservation,
            'room'=>$room,
            'reservation'=>$reservation
        ]);
    }

    #[Route('/{id}/guest', name: 'reservation_guest_info')]
    public function guestInfo(Request $request, EntityManagerInterface $manager, Reservation $reservation): Response
    {
        $guest= new Guest();
        $formGuestInfo = $this->createForm(GuestInfoType::class, $guest);
        $formGuestInfo->handleRequest($request);
        if($formGuestInfo->isSubmitted() && $formGuestInfo->isValid()){

            $reservation->setGuest($guest);
            $manager->persist($guest);
            $manager->flush();

            return $this->render('reservation/payment.html.twig');
        }

        return $this->render('reservation/guest.html.twig', [
            'formGuestInfo' => $formGuestInfo,
        ]);
    }
}
