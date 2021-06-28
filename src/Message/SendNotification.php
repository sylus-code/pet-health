<?php

namespace App\Message;

class SendNotification
{
    private int $notificationId;

    public function __construct(int $notificationId)
    {
        $this->notificationId = $notificationId;
    }

    public function getNotificationId(): int
    {
        return $this->notificationId;
    }
}