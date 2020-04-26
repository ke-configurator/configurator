<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CalculationGroupRepository")
 * @Gedmo\Loggable
 */
class CalculationGroup
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Calculation", mappedBy="calculationGroup")
     */
    private $calculations;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isActive;

    /**
     * Timestamp when the entity created.
     *
     * @var DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    private $created;

    /**
     * Timestamp when the entity updated.
     *
     * @var DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @var DateTime $deletedAt
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    private $deletedAt;

    public function __construct()
    {
        $this->calculations = new ArrayCollection();
        $this->setIsActive(false);
    }

    public function __toString()
    {
        return $this->title;
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return Collection|Calculation[]
     */
    public function getCalculations(): Collection
    {
        return $this->calculations;
    }

    public function addCalculation(Calculation $calculation): self
    {
        if (!$this->calculations->contains($calculation)) {
            $this->calculations[] = $calculation;
            $calculation->setCalculationGroup($this);
        }

        return $this;
    }

    public function removeCalculation(Calculation $calculation): self
    {
        if ($this->calculations->contains($calculation)) {
            $this->calculations->removeElement($calculation);
            // set the owning side to null (unless already changed)
            if ($calculation->getCalculationGroup() === $this) {
                $calculation->setCalculationGroup(null);
            }
        }

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}
