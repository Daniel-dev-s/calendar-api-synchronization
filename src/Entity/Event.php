<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Cassandra\Date;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $calendarId;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $start;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $endTime;


    /**
     * Event constructor.
     * @param string $name
     * @param string $calendarId
     * @param DateTime|null $start
     * @param DateTime|null $endTime
     */
    public function __construct(string $name, string $calendarId, ?DateTime $start, ?DateTime $endTime)
    {
        $this->name = $name;
        $this->calendarId = $calendarId;
        $this->start = $start;
        $this->endTime = $endTime;
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

    public function getCalendarId(): ?int
    {
        return $this->calendarId;
    }

    public function setCalendarId(int $calendarId): self
    {
        $this->calendarId = $calendarId;

        return $this;
    }

    public function getStart(): ?DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(?DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEndTime(): ?DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(?DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

}
