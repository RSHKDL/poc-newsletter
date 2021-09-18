<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @todo could use an embeddable entity (embedded inside user)
 * @ORM\Entity(repositoryClass=SubscriptionRepository::class)
 */
class Subscription
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToMany(targetEntity=Newsletter::class, inversedBy="subscriptions")
     */
    private Collection $newsletters;

    public function __construct()
    {
        $this->newsletters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Newsletter[]
     */
    public function getNewsletters(): Collection
    {
        return $this->newsletters;
    }

    public function addNewsletter(Newsletter $newsletter): self
    {
        if (!$this->newsletters->contains($newsletter)) {
            $this->newsletters[] = $newsletter;
        }

        return $this;
    }

    public function removeNewsletter(Newsletter $newsletter): self
    {
        $this->newsletters->removeElement($newsletter);

        return $this;
    }
}
