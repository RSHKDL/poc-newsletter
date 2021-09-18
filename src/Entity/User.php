<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="uuid")
     */
    private Uuid $uuid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $firstName;

    /**
     * @ORM\OneToOne(targetEntity=Subscription::class, cascade={"persist", "remove"})
     */
    private ?Subscription $subscription = null;

    public function __construct()
    {
        $this->uuid = Uuid::v4();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function setSubscription(?Subscription $subscription): self
    {
        $this->subscription = $subscription;

        return $this;
    }

    public function hasSubscription(): bool
    {
        return null !== $this->subscription;
    }
}
