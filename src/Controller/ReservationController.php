<?php

namespace App\Controller;

use App\Entity\Guest;
use App\Entity\Reservation;
use App\Entity\Room;
use App\Form\GuestInfoType;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('{_locale}/reservation')]
class ReservationController extends AbstractController
{

    #[Route('/room/{id}', name: 'reservation_choices')]
    public function choices(Request $request, Room $room, EntityManagerInterface $manager, ReservationRepository $repository): Response
    {
        $reservation = new Reservation();
        $formReservation = $this->createForm(ReservationType::class, $reservation);
        $formReservation->handleRequest($request);

        $today = new \DateTimeImmutable();

        if($formReservation->isSubmitted() && $formReservation->isSubmitted()){

            $reservation->setRoom($room);
            $reservation->setCreatedAt(new \DateTimeImmutable());

            $allResOfRoom = $repository->findBy(array('room'=> $room->getId())); // reservations de la chambre
            foreach ($allResOfRoom as $existingReservation){

                $checkIn = $reservation->getCheckIn();
                $checkOut= $reservation->getCheckOut();
                $roomId = $room->getId();

                if ($checkIn >= $existingReservation->getCheckIn() && $checkIn < $existingReservation->getCheckOut() ||
                    $checkOut > $existingReservation->getCheckIn() && $checkOut <= $existingReservation->getCheckOut()
                ){
                    $this->addFlash(
                        'notice',
                        'room already occupied on those dates, check the calendar below'
                    );

                    return $this->redirectToRoute('reservation_choices', [
                        'id' => $roomId
                    ]);
                }

                if($checkIn < $today){

                    $this->addFlash(
                        'notice',
                        'check in only possible for current day or days to come'
                    );

                    return $this->redirectToRoute('reservation_choices', [
                        'id' => $roomId
                    ]);
                }
            }

            $nbPeople = $reservation->getNumberOfPeople();
            $checkIn= $reservation->getCheckIn();
            $checkOut=$reservation->getCheckOut();
            $totalNights = $checkIn->diff($checkOut)->days;
            $costPerNight= $room->getPrice();
            $totalCost= $totalNights*$costPerNight*$nbPeople;

            $reservation->setCost($totalCost);

            $manager->persist($reservation);
            $manager->flush();

            return $this->redirectToRoute('reservation_guest_info', [
                'id'=>$reservation->getId()
            ]);

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
        $room = $reservation->getRoom();

        $guest= new Guest();

        $formGuestInfo = $this->createForm(GuestInfoType::class, $guest);
        $formGuestInfo->handleRequest($request);
        if($formGuestInfo->isSubmitted() && $formGuestInfo->isValid()){



            $reservation->setGuest($guest);
            $manager->persist($guest);
            $manager->flush();

            return $this->redirectToRoute('reservation_payment', [
                'id'=>$reservation->getId()
            ]);

        }

        return $this->render('reservation/guest.html.twig', [
            'formGuestInfo' => $formGuestInfo,
            'room'=>$room,
            'reservation'=>$reservation
        ]);
    }

    #[Route('/{id}/payment', name: 'reservation_payment')]
    public function payment(Reservation $reservation){

        $room = $reservation->getRoom();
        $guest = $reservation->getGuest();

        $roomName = $reservation->getRoom()->getName();
        $reservationId = $reservation->getId();

        $this->addFlash(
            'success',
            "Thank you for booking the {{$roomName}} room. Your reservation number is {{$reservationId}}"
        );

        return $this->render('reservation/payment.html.twig', [
            'room'=>$room,
            'reservation'=>$reservation,
            'guest'=>$guest
        ]);

    }

    #[Route('/{id}/cancel', name: 'reservation_cancel')]
    public function cancel(Reservation $reservation, EntityManagerInterface $manager, RoomRepository $roomRepository){

        $manager->remove($reservation);
        $manager->flush();

        $rooms = $roomRepository->findAll();

        return $this->render('room/index.html.twig', [
            'rooms'=>$rooms
        ]);

    }

}
