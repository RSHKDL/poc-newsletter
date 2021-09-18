<?php

namespace App\Entity;

use App\Repository\NewsletterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=NewsletterRepository::class)
 */
class Newsletter
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
    private string $title;

    /**
     * @ORM\Column(type="text")
     */
    private string $content;

    /**
     * @ORM\ManyToMany(targetEntity=Subscription::class, mappedBy="newsletters")
     */
    private Collection $subscriptions;

    public function __construct()
    {
        $this->uuid = Uuid::v4();
        $this->subscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection|Subscription[]
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscribtion(Subscription $subscribtion): self
    {
        if (!$this->subscriptions->contains($subscribtion)) {
            $this->subscriptions[] = $subscribtion;
            $subscribtion->addNewsletter($this);
        }

        return $this;
    }

    public function removeSubscribtion(Subscription $subscribtion): self
    {
        if ($this->subscriptions->removeElement($subscribtion)) {
            $subscribtion->removeNewsletter($this);
        }

        return $this;
    }
}
