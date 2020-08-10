<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Message;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class MessageRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Message::class);
        $this->em = $em;
    }

    public function save(Message $message): string
    {
        $message->setCreated(new DateTime());
        $message->setUuid(uuid_create(UUID_TYPE_RANDOM));

        $this->em->persist($message);
        $this->em->flush();

        return $message->getUuid();
    }
}