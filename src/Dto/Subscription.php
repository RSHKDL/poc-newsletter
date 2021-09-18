<?php

namespace App\Dto;

use Doctrine\Common\Collections\Collection;

class Subscription
{
    public string $email;
    public string $firstName;
    public Collection $newsletters;
}