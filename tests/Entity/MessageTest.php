<?php

namespace App\Tests\Entity;

use App\Entity\Message;
use DateTime;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{

    public function testSetRead()
    {
        $message = new Message();
        $message->setRead(true);
        self::assertTrue($message->isRead());
    }

    public function testSetUuid()
    {
        $message = new Message();
        $uuid = '123456';
        $message->setUuid($uuid);
        self::assertSame($uuid, $message->getUuid());
    }

    public function testGetContent()
    {
        $message = new Message();
        $content = '123456';
        $message->setContent($content);
        self::assertSame($content, $message->getContent());
    }

    public function testGetCreated()
    {
        $message = new Message();
        $date = new DateTime();
        $message->setCreated($date);
        self::assertSame($date, $message->getCreated());
    }

}
