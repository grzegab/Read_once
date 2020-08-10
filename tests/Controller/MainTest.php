<?php

namespace App\Tests\Controller;

use App\Controller\Main;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class MainTest extends TestCase
{

    public function testReadMessage()
    {
        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('<p>HTML</p>');

        $controller = new Main($twig);
        $desc = $controller->readMessage('cebf0224-5f8d-4e26-a212-16b46600b983');

        self::assertNotEmpty($desc->getContent());
        self::assertSame(Response::HTTP_OK, $desc->getStatusCode());

        $controller = new Main($twig);
        $descEmpty = $controller->readMessage('123');

        self::assertNotEmpty($descEmpty->getContent());
        self::assertSame(Response::HTTP_BAD_REQUEST, $descEmpty->getStatusCode());
    }

    public function testCreateMessage()
    {
        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('<p>HTML</p>');

        $controller = new Main($twig);
        $desc = $controller->createMessage();

        self::assertNotEmpty($desc->getContent());
        self::assertSame(Response::HTTP_OK, $desc->getStatusCode());
    }

    public function testDescription()
    {
        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('<p>HTML</p>');

        $controller = new Main($twig);
        $desc = $controller->description();

        self::assertNotEmpty($desc->getContent());
        self::assertSame(Response::HTTP_OK, $desc->getStatusCode());
    }
}
