<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Service;
use App\Entity\TimeSlot;
use App\Repository\BookingRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ServiceController extends AbstractController
{
    #[Route('/services', name: 'app_services')]
    public function index(ServiceRepository $serviceRepository): Response
    {
        $services = $serviceRepository->findBy([
            'isActive' => true
        ]);

        return $this->render('service/index.html.twig', [

            'services' => $services,

        ]);
    }

    #[Route('/service/{id}', name: 'app_service_show')]
    public function show(Service $service): Response
    {
        if (!$service->isActive()) {

            return $this->redirectToRoute('app_services');
        }

        return $this->render('service/show.html.twig', [

            'service' => $service,

        ]);
    }

    #[Route('/booking/create/{id}', name: 'app_booking_create')]
    public function createBooking(
        TimeSlot $timeSlot,
        EntityManagerInterface $entityManager,
        BookingRepository $bookingRepository
    ): Response {

        if (!$this->getUser()) {

            return $this->redirectToRoute('app_login');
        }

        $existingBooking = $bookingRepository->findOneBy([

            'timeSlot' => $timeSlot,

        ]);

        if ($existingBooking) {

            $this->addFlash('error', 'Dieser Termin ist bereits gebucht.');

            return $this->redirectToRoute('app_services');
        }

        $booking = new Booking();

        $booking->setUser($this->getUser());

        $booking->setTimeSlot($timeSlot);

        $booking->setStatus('pending');

        $booking->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($booking);

        $entityManager->flush();

        $this->addFlash('success', 'Booking erfolgreich erstellt.');

        return $this->redirectToRoute('app_services');
    }

    #[Route('/profile/bookings', name: 'app_profile_bookings')]
    public function myBookings(BookingRepository $bookingRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {

            return $this->redirectToRoute('app_login');
        }

        $bookings = $bookingRepository->findBy([
            'user' => $user,
        ]);

        $totalBookings = count($bookings);

        $confirmedBookings = 0;

        $cancelledBookings = 0;

        foreach ($bookings as $booking) {

            if ($booking->getStatus() === 'confirmed') {

                $confirmedBookings++;
            }

            if ($booking->getStatus() === 'cancelled') {

                $cancelledBookings++;
            }
        }

        return $this->render('service/bookings.html.twig', [

            'bookings' => $bookings,

            'totalBookings' => $totalBookings,

            'confirmedBookings' => $confirmedBookings,

            'cancelledBookings' => $cancelledBookings

        ]);
    }

    #[Route('/booking/cancel/{id}', name: 'app_booking_cancel')]
    public function cancelBooking(
        Booking $booking,
        EntityManagerInterface $entityManager
    ): Response {

        $user = $this->getUser();

        if (!$user) {

            return $this->redirectToRoute('app_login');
        }

        if ($booking->getUser() !== $user) {

            return $this->redirectToRoute('app_services');
        }

        $booking->setStatus('cancelled');

        $entityManager->flush();

        $this->addFlash('success', 'Booking storniert.');

        return $this->redirectToRoute('app_profile_bookings');
    }
}