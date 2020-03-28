<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "get",
 *         "post"={"security"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *         "get",
 *         "put"={"security"="is_granted('ROLE_ADMIN') or object.owner == user"},
 *     }
 * )
 * @ORM\Entity
 */
class Proposal
{
    const PROPOSED = 0;
    const RUNNING = 1;
    const CANCELED = 2;
    const COMPLETED = 3;

    const PROGRESSION_PERCENT = 0;
    const PROGRESSION_NUMBER = 1;
    const PROGRESSION_NONE = 2;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="proposals")
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="blob")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $status = Proposal::PROPOSED;

    /**
     * @ORM\Column(type="integer")
     */
    private $progression = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $progressionMax = 100;

    /**
     * @ORM\Column(type="integer")
     */
    private $progressionType = Proposal::PROGRESSION_NONE;

    /**
     * @ORM\Column(type="integer")
     */
    private $likes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="proposals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $person;

    public function __construct()
    {
        $this->category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->category->contains($category)) {
            $this->category->removeElement($category);
        }

        return $this;
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

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getProgression(): ?int
    {
        return $this->progression;
    }

    public function setProgression(int $progression): self
    {
        $this->progression = $progression;

        return $this;
    }

    public function getProgressionMax(): ?int
    {
        return $this->progressionMax;
    }

    public function setProgressionMax(int $progressionMax): self
    {
        $this->progressionMax = $progressionMax;

        return $this;
    }

    public function getProgressionType(): ?int
    {
        return $this->progressionType;
    }

    public function setProgressionType(int $progressionType): self
    {
        $this->progressionType = $progressionType;

        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
    }
}