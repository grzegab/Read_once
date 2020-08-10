<?php

namespace App\Tests\Controller;

use App\Controller\Main;
use App\Repository\MessageRepository;
use Error;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class MainTest extends TestCase
{

    public function testReadMessage()
    {
        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('<p>HTML</p>');

        $messageRepository = $this->createMock(MessageRepository::class);

        $controller = new Main($twig, $messageRepository);
        $desc = $controller->readMessage('cebf0224-5f8d-4e26-a212-16b46600b983');

        self::assertNotEmpty($desc->getContent());
        self::assertSame(Response::HTTP_NOT_FOUND, $desc->getStatusCode());

        $controller = new Main($twig, $messageRepository);
        $descEmpty = $controller->readMessage('123');

        self::assertNotEmpty($descEmpty->getContent());
        self::assertSame(Response::HTTP_BAD_REQUEST, $descEmpty->getStatusCode());
    }

    public function testCreateMessage()
    {
        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('<p>HTML</p>');

        $messageRepository = $this->createMock(MessageRepository::class);
        $request = $this->createMock(Request::class);

        $this->expectException(Error::class);
        $controller = new Main($twig, $messageRepository);
        $desc = $controller->createMessage($request);

        self::assertNotEmpty($desc->getContent());
        self::assertSame(Response::HTTP_OK, $desc->getStatusCode());
    }

    public function testDescription()
    {
        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('<p>HTML</p>');

        $messageRepository = $this->createMock(MessageRepository::class);

        $controller = new Main($twig, $messageRepository);
        $desc = $controller->description();

        self::assertNotEmpty($desc->getContent());
        self::assertSame(Response::HTTP_OK, $desc->getStatusCode());
    }
}
