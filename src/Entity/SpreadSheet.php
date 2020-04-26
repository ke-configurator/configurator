<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpreadSheetRepository")
 * @Gedmo\Loggable
 */
class SpreadSheet
{
    const STATUS_UNCONFIRMED = 'unconfirmed';
    const STATUS_SYNCED      = 'synced';
    const STATUS_MISSING     = 'missing';

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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Versioned
     */
    private $uid;

    /**
     * @ORM\Column(type="string", length=20)
     * @Gedmo\Versioned
     */
    private $status;

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

    /**
     * SpreadSheet constructor.
     */
    public function __construct()
    {
        $this
            ->setIsActive(false)
            ->setStatus(self::STATUS_UNCONFIRMED);
    }

    public function __toString()
    {
        return sprintf('%s [%s]', $this->getName(), substr($this->getUid(), 0, 8));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(string $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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
