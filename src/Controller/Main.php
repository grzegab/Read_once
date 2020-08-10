<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Message;
use App\Forms\MessageForm;
use App\Repository\MessageRepository;
use RuntimeException;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Main extends AbstractController
{
    private Environment $twig;
    private MessageRepository $messageRepository;

    public function __construct(Environment $twig, MessageRepository $messageRepository)
    {
        $this->twig = $twig;
        $this->messageRepository = $messageRepository;
    }

    /**
     * Render description page (aka. landing page).
     * @return Response
     */
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

    /**
     * Page for creating message (view with form).
     * @param Request $request
     * @return Response
     */
    public function createMessage(Request $request): Response
    {
        $response = new Response();

        try {
            /*
             * Create view for Message form
             */
            $form = $this->createForm(MessageForm::class);
            $form->handleRequest($request);
            /*
             * If form is valid show page with url.
             */
            if ($form->isSubmitted() && $form->isValid()) {
                $message = $form->getData();
                $savedMessageUuid = $this->messageRepository->save($message);
                $html = $this->twig->render('Main/saved.html.twig', ['uuid' => $savedMessageUuid]);
                $response->setContent($html);
            } else { /* Render default page with form */
                $html = $this->twig->render('Main/create.html.twig', ['form' => $form->createView()]);
                $response->setContent($html);
            }
        } catch (LoaderError|RuntimeError|SyntaxError|Exception $e) {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * Show message content based on uuid.
     * @param string $messageUuid
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function readMessage(string $messageUuid): Response
    {
        $response = new Response();

        try {
            /*
             * If uuid is not valid - return 404.
             */
            if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $messageUuid)) {
                throw new LogicException('Not uuid');
            }

            /** @var Message|null $message */
            $message = $this->messageRepository->findOneBy(['uuid' => $messageUuid]);

            /*
             * If message is not found or read - return 404
             */
            if ($message === null || $message->isRead()) {
                throw new RuntimeException('Not found');
            }

            /*
             * Show message content
             */
            $html = $this->twig->render('Main/read.html.twig', ['content' => $message->getContent()]);
            $response->setContent($html);

            /*
             * Now remove message by setting read to true and message content to random string.
             */
            $message->setRead(true);
            $message->setContent('deleted');
            $this->messageRepository->save($message);
        } catch (LogicException $e) {
            $response->setContent($e->getMessage());
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        } catch (RuntimeException $e) {
            $error404 = $this->twig->render('Errors/404.html.twig', ['message' => $e->getMessage()]);
            $response->setContent($error404);
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}