<?php

namespace App\Entity;

use App\Repository\CalendarRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CalendarRepository::class)
 */
class Calendar
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $googleId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastSync;

    /**
     * Calendar constructor.
     * @param $name
     * @param $googleId
     * @param $lastSync
     */
    public function __construct($name, $googleId, $lastSync)
    {
        $this->name = $name;
        $this->googleId = $googleId;
        $this->lastSync = $lastSync;
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

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(string $googleId): self
    {
        $this->googleId = $googleId;

        return $this;
    }

    public function getLastSync(): ?\DateTimeInterface
    {
        return $this->lastSync;
    }

    public function setLastSync(\DateTimeInterface $lastSync): self
    {
        $this->lastSync = $lastSync;

        return $this;
    }
    public function getLastSyncString(): string
    {
        return $this->getLastSync()->format("d-M-Y H:m:s");
    }
}
