<?php

namespace App\MessageHandler;

use App\Entity\Notification;
use App\Entity\Prevention;
use App\Message\PreventionCreated;
use App\Repository\PreventionRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

class SendEmailOnPreventionCreatedHandler implements MessageHandlerInterface
{
    private PreventionRepository $preventionRepository;
    private MailerInterface $mailer;
    private LoggerInterface $logger;

    // wstrzykuje serwis który umie wysyłac email = Mailer
    public function __construct(LoggerInterface $logger, MailerInterface $mailer, PreventionRepository $preventionRepository)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->preventionRepository = $preventionRepository;
    }

    public function __invoke(PreventionCreated $preventionCreated)
    {
        $prevention = $this->preventionRepository->findOneBy(['id' => $preventionCreated->getPreventionId()]);
        $preventionTypeName = '';

        if ($prevention->getType() == Prevention::VACCINE) {
            $preventionTypeName = 'szczepienie';
        } elseif ($prevention->getType() == 1) {
            $preventionTypeName = 'przeciw pasożytom';
        } elseif ($prevention->getType() == 2) {
            $preventionTypeName = 'pielęgnacja';
        }

        $email = (new Email())
            ->to($prevention->getAnimal()->getUser()->getEmail())
            ->from('pethealth.notification@gmail.com')
            ->subject('Dodano nową profilaktykę')
            ->text(sprintf('Utworzono nową profilaktykę typu %s, %s. W dniu: %s. 
                Powiadomienie przypominające zostanie wysłane na 2 tygodnie oraz 1 dzień przed planowaną wizytą.',
                $preventionTypeName,
                $prevention->getDescription(),
                $prevention->getDate()->format('Y-m-d')
            ));

        // wysyłam emaila
        $this->mailer->send($email);
        $this->logger->info('wysyłam emaila dla notyfikacji', ['event' => $preventionCreated]);
    }

}