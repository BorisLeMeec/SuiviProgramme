<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeviceRepository")
 */
class Device
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=700)
     */
    private $token;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Proposal", mappedBy="followers")
     */
    private $favorites;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Person", mappedBy="subscribers")
     */
    private $peoples;

    public function __construct()
    {
        $this->favorites = new ArrayCollection();
        $this->peoples = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return Collection|Proposal[]
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Proposal $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites[] = $favorite;
            $favorite->addFollower($this);
        }

        return $this;
    }

    public function removeFavorite(Proposal $favorite): self
    {
        if ($this->favorites->contains($favorite)) {
            $this->favorites->removeElement($favorite);
            $favorite->removeFollower($this);
        }

        return $this;
    }

    /**
     * @return Collection|Person[]
     */
    public function getPeoples(): Collection
    {
        return $this->peoples;
    }

    public function addPeople(Person $people): self
    {
        if (!$this->peoples->contains($people)) {
            $this->peoples[] = $people;
            $people->addSubscriber($this);
        }

        return $this;
    }

    public function removePeople(Person $people): self
    {
        if ($this->peoples->contains($people)) {
            $this->peoples->removeElement($people);
            $people->removeSubscriber($this);
        }

        return $this;
    }
}
