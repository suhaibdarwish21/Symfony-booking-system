<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ServiceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Service::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [

            IdField::new('id')
                ->hideOnForm(),

            TextField::new('name', 'Service Name')
                ->setRequired(true),

            TextareaField::new('description', 'Beschreibung')
                ->setRequired(true)
                ->hideOnIndex(),

            NumberField::new('price', 'Preis (€)')
                ->setNumDecimals(2)
                ->setRequired(true),

            NumberField::new('durationMinutes', 'Dauer (Minuten)')
                ->setRequired(true),

            BooleanField::new('isActive', 'Aktiv'),

            Field::new('imageFile', 'Bild hochladen')
                ->setFormType(VichImageType::class)
                ->onlyOnForms(),

            TextField::new('imageName', 'Bild')
                ->onlyOnIndex(),

        ];
    }
}