<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Message;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

class MessageRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Message::class);
        $this->em = $em;
    }

    /**
     * Return false if UUID is not valid.
     * @param $uuid
     * @return bool
     */
    public function validateUuid($uuid): bool
    {
        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid)) {
            return false;
        }

        return true;
    }

    /**
     * Return true if message is ok, if read return false.
     * @param $uuid
     * @return bool
     */
    public function checkMessageRead($uuid): bool
    {
        $message = $this->findOneBy(['uuid' => $uuid]);
        if ($message === null) {
            throw new RuntimeException('Message does not exists.');
        }

        if ($message->isRead()) {
            return false;
        }

        return true;
    }

    /**
     * Saves the message.
     * @param Message $message
     * @return string
     */
    public function save(Message $message): string
    {
        $message->setCreated(new DateTime());
        $message->setUuid(uuid_create(UUID_TYPE_RANDOM));

        $this->em->persist($message);
        $this->em->flush();

        return $message->getUuid();
    }
}