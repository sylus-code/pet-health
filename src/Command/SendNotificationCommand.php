<?php


namespace App\Command;


use App\Message\PreventionCreated;
use App\Repository\NotificationRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SendNotificationCommand extends Command
{
    protected static $defaultName = 'app:send-notification';

    private NotificationRepository $notificationRepository;
    private MessageBusInterface $bus;
    private LoggerInterface $logger;

    public function __construct(
        NotificationRepository $notificationRepository,
        MessageBusInterface $bus,
        LoggerInterface $logger,
        string $name = null
    ) {
        $this->notificationRepository = $notificationRepository;
        $this->logger = $logger;
        $this->bus = $bus;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Checks if there is notification to send.')
            ->setHelp('This command lets you send notifications.');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $notifications = $this->notificationRepository->findBy(['status' => false]);
        $this->logger->info('znalaziono ' . count($notifications));

        foreach ($notifications as $notification) {
            $this->logger->info('dispatchuje event');
            // dodaj dispatchowanie eventu np SendEmail
        }
        return Command::SUCCESS;
    }
}