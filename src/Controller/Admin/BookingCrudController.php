<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use Doctrine\ORM\EntityManagerInterface;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class BookingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Booking::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud

            ->setPageTitle('index', 'Buchungen')

            ->setDefaultSort([

                'createdAt' => 'DESC'

            ]);
    }

    public function configureFields(string $pageName): iterable
    {
        return [

            ChoiceField::new('status')

                ->setChoices([

                    'Ausstehend' => 'pending',

                    'Bestätigt' => 'confirmed',

                    'Storniert' => 'cancelled',

                ])

                ->renderAsBadges([

                    'pending' => 'warning',

                    'confirmed' => 'success',

                    'cancelled' => 'danger',

                ]),

            DateTimeField::new('createdAt', 'Erstellt am'),

            AssociationField::new('user', 'Benutzer'),

            AssociationField::new('timeSlot', 'Termin'),

        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $approve = Action::new('approveBooking', 'Akzeptieren')

            ->linkToCrudAction('approveBooking')

            ->setCssClass('btn btn-success');

        $reject = Action::new('rejectBooking', 'Ablehnen')

            ->linkToCrudAction('rejectBooking')

            ->setCssClass('btn btn-danger');

        return $actions

            ->add(Crud::PAGE_INDEX, $approve)

            ->add(Crud::PAGE_INDEX, $reject)

            ->add(Crud::PAGE_DETAIL, $approve)

            ->add(Crud::PAGE_DETAIL, $reject);
    }

    #[AdminRoute(
        path: '/booking/{entityId}/approve',
        name: 'booking_approve'
    )]
    public function approveBooking(
        EntityManagerInterface $entityManager
    ): RedirectResponse {

        $booking = $this->getContext()
            ->getEntity()
            ->getInstance();

        $booking->setStatus('confirmed');

        $entityManager->flush();

        $this->addFlash(

            'success',

            'Buchung wurde akzeptiert.'

        );

        return $this->redirect(

            $this->generateUrl('admin')

        );
    }

    #[AdminRoute(
        path: '/booking/{entityId}/reject',
        name: 'booking_reject'
    )]
    public function rejectBooking(
        EntityManagerInterface $entityManager
    ): RedirectResponse {

        $booking = $this->getContext()
            ->getEntity()
            ->getInstance();

        $booking->setStatus('cancelled');

        $entityManager->flush();

        $this->addFlash(

            'danger',

            'Buchung wurde abgelehnt.'

        );

        return $this->redirect(

            $this->generateUrl('admin')

        );
    }
}