<?php

namespace App\Subscription\Dto;

use Doctrine\Common\Collections\Collection;

class Subscription
{
    public string $email;
    public ?string $firstName = null;
    public Collection $newsletters;
}