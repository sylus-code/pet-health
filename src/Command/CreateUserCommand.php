<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserCommand extends Command
{

    protected static $defaultName = 'app:create-user';

    private $passwordEncoder;
    private $userRepository;
    private $entityManager;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        string $name = null
    )
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;

        parent::__construct($name);
    }
    protected function configure()
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'Utwórz konto właściciela podając email')
            ->addArgument('password', InputArgument::REQUIRED, 'Utworz haslo dostepu')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'User creator',
            '============='
        ]);

        $user = new User();
        $username = $input->getArgument('username');
        $plainPassword = $input->getArgument('password');


        $user->setEmail($username);
        $encoded = $this->passwordEncoder->encodePassword($user, $plainPassword);
        $user->setPassword($encoded);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln([
            'Utworzono nowe konto! '.$username
        ]);

        return Command::SUCCESS;
    }
}
