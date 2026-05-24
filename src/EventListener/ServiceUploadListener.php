<?php

namespace App\EventListener;

use App\Entity\Service;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Mapping\PropertyMappingFactory;

final class ServiceUploadListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => 'beforePersist',
            BeforeEntityUpdatedEvent::class => 'beforeUpdate',
        ];
    }

    public function beforePersist(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Service)) {
            return;
        }

        $this->handleFileUpload($entity);
    }

    public function beforeUpdate(BeforeEntityUpdatedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Service)) {
            return;
        }

        $this->handleFileUpload($entity);
    }

    private function handleFileUpload(Service $service): void
    {
        if (null === $service->getImageFile()) {
            return;
        }

        $service->setUpdatedAt(new \DateTime());
    }
}
