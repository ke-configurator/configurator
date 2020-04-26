<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CalculationRepository")
 * @UniqueEntity("spreadSheet", message="this is already in use.")
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Calculation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Versioned
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SpreadSheet")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $spreadSheet;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CalculationGroup", inversedBy="calculations")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $calculationGroup;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     * @Gedmo\Versioned
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
     * @var User $createdBy
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    private $createdBy;

    /**
     * Timestamp when the entity updated.
     *
     * @var DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    private $updated;

    /**
     * @var User $updatedBy
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="updated_by", referencedColumnName="id")
     * @Gedmo\Versioned
     */
    private $updatedBy;

    /**
     * @var DateTime $deletedAt
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    private $deletedAt;

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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCreated(): ?DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getDeletedAt(): ?DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getSpreadSheet(): ?SpreadSheet
    {
        return $this->spreadSheet;
    }

    public function setSpreadSheet(?SpreadSheet $spreadSheet): self
    {
        $this->spreadSheet = $spreadSheet;

        return $this;
    }

    public function getCalculationGroup(): ?CalculationGroup
    {
        return $this->calculationGroup;
    }

    public function setCalculationGroup(?CalculationGroup $calculationGroup): self
    {
        $this->calculationGroup = $calculationGroup;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?User $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }
}