<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Booking;
use App\Entity\Service;
use App\Entity\TimeSlot;
use App\Entity\User;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('@EasyAdmin/page/content.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Booking System');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToRoute('Services', 'fas fa-list', 'admin_service_index');
        yield MenuItem::linkToRoute('Time Slots', 'fas fa-clock', 'admin_time_slot_index');
        yield MenuItem::linkToRoute('Bookings', 'fas fa-calendar', 'admin_booking_index');
        yield MenuItem::linkToRoute('Users', 'fas fa-users', 'admin_user_index');
    }
}
