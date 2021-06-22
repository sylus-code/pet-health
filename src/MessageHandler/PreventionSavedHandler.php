<?php

namespace App\MessageHandler;

use App\Message\PreventionSaved;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class PreventionSavedHandler implements MessageHandlerInterface
{
    public function __invoke(PreventionSaved $message)
    {
        echo 'w tym miejscu wysylam wiadomosc';
    }

}