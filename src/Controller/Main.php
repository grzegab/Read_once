<?php

declare(strict_types=1);

namespace App\Controller;

use LogicException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Main
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function description(): Response
    {
        $response = new Response();

        try {
            $html = $this->twig->render('Main/description.html.twig');
            $response->setContent($html);
        } catch (LoaderError|RuntimeError|SyntaxError|Exception $e) {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    public function createMessage(): Response
    {
        $response = new Response();

        try {
            $html = $this->twig->render('Main/create.html.twig');
            $response->setContent($html);
        } catch (LoaderError|RuntimeError|SyntaxError|Exception $e) {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    public function readMessage(string $messageUuid): Response
    {
        $response = new Response();

        try {
            if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $messageUuid)) {
                throw new LogicException('Not uuid');
            }

            $html = $this->twig->render('Main/read.html.twig');
            $response->setContent($html);
        } catch (LogicException $e) {
            $response->setContent($e->getMessage());
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        } catch (LoaderError|RuntimeError|SyntaxError|Exception $e) {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}