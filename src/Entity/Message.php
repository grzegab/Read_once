<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Message
 * @package App\Entity\Message
 * @ORM\Entity()
 * @ORM\Table(name="message")
 */
class Message
{
    /**
     * Main id for entry.
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="commissions_seq", initialValue=1)
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * Uuid used in generating url.
     * @ORM\Column(type="guid", length=255, nullable=false, unique=true)
     */
    private string $uuid;
    /**
     * Message content. Override after read.
     * @ORM\Column(type="string", length=255)
     */
    private string $content;
    /**
     * Flag to determine if message was read.
     * @ORM\Column(type="boolean", nullable=false)
     */
    private bool $read = false;
    /**
     * When message was created.
     * @ORM\Column(type="datetime", nullable=false)
     */
    private DateTime $created;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return bool
     */
    public function isRead(): bool
    {
        return $this->read;
    }

    /**
     * @param bool $read
     */
    public function setRead(bool $read): void
    {
        $this->read = $read;
    }

    /**
     * @return DateTime
     */
    public function getCreated(): DateTime
    {
        return $this->created;
    }

    /**
     * @param DateTime $created
     */
    public function setCreated(DateTime $created): void
    {
        $this->created = $created;
    }
}