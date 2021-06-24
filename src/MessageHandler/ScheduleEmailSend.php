<?php

namespace App\MessageHandler;

use App\Entity\Notification;
use App\Entity\Prevention;
use App\Message\PreventionCreated;
use App\Repository\NotificationRepository;
use App\Repository\PreventionRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ScheduleEmailSend implements MessageHandlerInterface
{
    private PreventionRepository $preventionRepository;
    private NotificationRepository $notificationRepository;
    private LoggerInterface $logger;

    public function __construct(PreventionRepository $preventionRepository,
                                NotificationRepository $notificationRepository,
                                LoggerInterface $logger)
    {
        $this->preventionRepository = $preventionRepository;
        $this->notificationRepository = $notificationRepository;
        $this->logger = $logger;
    }

    public function __invoke(PreventionCreated $preventionCreated)
    {
        $prevention = $this->preventionRepository->findOneBy(['id' => $preventionCreated->getPreventionId()]);

        $this->addNotification("2 weeks", $prevention);
        $this->addNotification("1 day", $prevention);

        $this->logger->info('Powiadomienia zostały dodane pomyślnie.');
    }

    // powiadomienie przed wydarzeniem z wyprzedzeniem = $whenToNotify np: "2 weeks", "1 day"
    private function addNotification(string $whenToNotify, Prevention $prevention): void
    {
        $sendDate = date_sub($prevention->getDate(), date_interval_create_from_date_string($whenToNotify));

        $notification = new Notification();
        $notification->setEmail($prevention->getAnimal()->getUser()->getEmail());
        $notification->setContent($prevention->getDescription());
        $notification->setSendDate($sendDate);

        $this->notificationRepository->save($notification);
    }
}
