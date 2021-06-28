<?php

namespace App\MessageHandler;

use App\Message\SendNotification;
use App\Repository\NotificationRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

class SendEmailForNotification implements MessageHandlerInterface
{
    private NotificationRepository $notificationRepository;
    private MailerInterface $mailer;
    private LoggerInterface $logger;

    private const SENDER = 'pethealth.notification@gmail.com';

    // wstrzykuje serwis który umie wysyłac email = Mailer
    public function __construct(LoggerInterface $logger, MailerInterface $mailer, NotificationRepository $notificationRepository)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->notificationRepository = $notificationRepository;
    }

    public function __invoke(SendNotification $sendNotification): void
    {
        $notification = $this->notificationRepository->findOneBy(['id' => $sendNotification->getNotificationId()]);
        $email = (new Email())
            ->to($notification->getEmail())
            ->from(self::SENDER)
            ->subject($notification->getTitle())
            ->text($notification->getContent());

        // wysyłam emaila
        $this->mailer->send($email);
        $this->logger->info('Wysyłam emaila dla notyfikacji o zbliżającym się terminie Prevention', ['event' => $notification->getTitle()]);

        $notification->setStatus(true);
        $this->notificationRepository->save($notification);
    }

}