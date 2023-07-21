<?php

namespace App\MessageHandler;

use App\Entity\Prevention;
use App\Message\PreventionCreated;
use App\Repository\PreventionRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

class SendEmailOnPreventionCreated implements MessageHandlerInterface
{
    private const SENDER = 'pethealth.notification@gmail.com';
    private PreventionRepository $preventionRepository;
    private LoggerInterface $logger;
    private MailerInterface $mailer;

    public function __construct(
        LoggerInterface $logger,
        MailerInterface $mailer,
        PreventionRepository $preventionRepository
    ) {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->preventionRepository = $preventionRepository;
    }

    public function __invoke(PreventionCreated $preventionCreated): void
    {
        $prevention = $this->preventionRepository->findOneBy(['id' => $preventionCreated->getPreventionId()]);

        if ($prevention == null) {
            echo 'null prevention value';
            return;
        }

        $email = (new Email())
            ->to($prevention->getAnimal()->getUser()->getEmail())
            ->from(self::SENDER)
            ->subject('Dodano nowy zabieg profilaktyczny')
            ->text(
                sprintf(
                    'Dodano nowy zabieg profilaktyczny typu: %s, %s. W dniu: %s. ',
                    $this->createPreventionTypeName($prevention),
                    $prevention->getDescription(),
                    $prevention->getDate()->format('Y-m-d')
                )
            );
        $this->mailer->send($email);
        $this->logger->info('Wysyłam maila dla nowo dodanego Prevention', ['event' => $prevention->getDescription()]);
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
}
