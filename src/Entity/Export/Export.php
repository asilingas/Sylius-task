<?php

namespace App\Entity\Export;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Entity which contains all the data needed for an export request.
 *
 * @ORM\Entity(repositoryClass="App\Repository\ExportRepository")
 * @ORM\Table(name="export")
 */
class Export
{
    const STATUS_CREATED = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_DONE = 2;
    const STATUS_FAILED = 3;
    const TYPE_PRODUCT = 'product';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\AdminUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private UserInterface $user;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private \DateTime $created;

    /**
     * @ORM\Column(name="updated", type="datetime", nullable=false)
     */
    private \DateTime $updated;

    /**
     * @ORM\Column(name="type", type="string", length=255)
     */
    private string $type;

    /**
     * @ORM\Column(name="fileName", type="string", length=255, nullable=false)
     */
    private string $filename;

    /**
     * @ORM\Column(name="total_items", type="integer")
     */
    private int $totalItems;

    /**
     * @ORM\Column(name="processed_items", type="integer")
     */
    private int $processedItems;

    /**
     * @ORM\Column(name="status", type="smallint")
     */
    private int $status;

    public function __construct()
    {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
        $this->totalItems = 0;
        $this->processedItems = 0;
        $this->status = self::STATUS_CREATED;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): Export
    {
        $this->user = $user;

        return $this;
    }

    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    public function setCreated(\DateTime $created): Export
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): \DateTime
    {
        return $this->updated;
    }

    public function setUpdated(\DateTime $updated): Export
    {
        $this->updated = $updated;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): Export
    {
        $this->type = $type;

        return $this;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): Export
    {
        $this->filename = $filename;

        return $this;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function setTotalItems(int $totalItems): Export
    {
        $this->totalItems = $totalItems;

        return $this;
    }

    public function getProcessedItems(): int
    {
        return $this->processedItems;
    }

    public function setProcessedItems(int $processedItems): Export
    {
        $this->processedItems = $processedItems;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): Export
    {
        $this->status = $status;

        return $this;
    }
}
