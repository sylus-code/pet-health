<?php


namespace App\Message;


class PreventionCreated
{
    private int $preventionId;

    public function __construct(int $preventionId)
    {
        $this->preventionId = $preventionId;
    }

    public function getPreventionId(): int
    {
        return $this->preventionId;
    }

}