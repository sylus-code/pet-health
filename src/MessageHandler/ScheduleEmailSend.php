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

    public function __construct(
        PreventionRepository $preventionRepository,
        NotificationRepository $notificationRepository,
        LoggerInterface $logger
    ) {
        $this->preventionRepository = $preventionRepository;
        $this->notificationRepository = $notificationRepository;
        $this->logger = $logger;
    }

    public function __invoke(PreventionCreated $preventionCreated)
    {
        $prevention = $this->preventionRepository->findOneBy(['id' => $preventionCreated->getPreventionId()]);
        $this->addNotification("2 weeks", $prevention);
        $this->addNotification("1 day", $prevention);
    }

    private function addNotification(string $whenToNotify, Prevention $prevention): void
    {
        $this->logger->info(
            sprintf(
                "Planuje wysyłkę emaila na dzień: %s",
                $prevention->getDate()->format(
                    'Y-m-d H:i'
                )
            )
        );
        $temp = clone $prevention->getDate();

        $interval = \DateInterval::createFromDateString($whenToNotify);
        $sendDate = $temp->sub($interval);

        $today = new \DateTime('now');

        if ($sendDate < $today) {
            $this->logger->info(
                'Data wysyłki jest w przeszłości. Nie wysyłam.',
                [
                    'sendDate' => $sendDate,
                    'today' => $today
                ]
            );
            return;
        }

        $notification = new Notification();
        $notification->setEmail($prevention->getAnimal()->getUser()->getEmail());
        $notification->setTitle(
            sprintf(
                'Powiadomienie o zabiegu profilaktycznym typu: %s',
                $this->createPreventionTypeName($prevention)
            )
        );
        $notification->setContent($this->createContent($prevention));
        $notification->setSendDate($sendDate);

        $this->notificationRepository->save($notification);
        $this->logger->info(sprintf('Dodano nową notyfikację %s', $notification->getId()));
    }

    public function createPreventionTypeName(Prevention $prevention): string
    {
        $preventionTypeName = '';

        if ($prevention->getType() == Prevention::VACCINE) {
            $preventionTypeName = 'Szczepienie';
        } elseif ($prevention->getType() == Prevention::PARASITE_PROTECTION) {
            $preventionTypeName = 'Zabezpieczenie przeciw pasożytom';
        } elseif ($prevention->getType() == Prevention::CARE) {
            $preventionTypeName = 'Pielęgnacja';
        }

        return $preventionTypeName;
    }

    private function createContent(Prevention $prevention): string
    {
        return sprintf(
            'Zbliża się zabieg profilaktyczny typu: %s, %s. W dniu: %s. ',
            $this->createPreventionTypeName($prevention),
            $prevention->getDescription(),
            $prevention->getDate()->format('Y-m-d')
        );
    }
}
