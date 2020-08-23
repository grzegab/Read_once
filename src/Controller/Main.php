<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Message;
use App\Forms\MessageForm;
use App\Forms\VerifyForm;
use App\Repository\MessageRepository;
use RuntimeException;
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
             * If uuid is not valid - return 500.
             */
            if (!$this->messageRepository->validateUuid($messageUuid)) {
                throw new RuntimeException('Broken message ID.');
            }

            /** @var Message|null $message */
            $message = $this->messageRepository->findOneBy(['uuid' => $messageUuid]);

            /*
             * If message is not found or read - return 404
             */
            if ($message === null) {
                throw new RuntimeException('Message does not exists.');
            }

            /*
             * If message is read - return 500
             */
            if ($message->isRead()) {
                throw new RuntimeException('Message already read.');
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
        } catch (RuntimeException | LoaderError | RuntimeError | SyntaxError $e) {
            $error500 = $this->twig->render('Errors/500.html.twig', ['message' => $e->getMessage()]);
            $response->setContent($error500);
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    public function statusMessage($messageUuid): Response
    {
        $response = new Response();

        try {
            /*
             * Check if uuid is valid
             */
            if (!$this->messageRepository->validateUuid($messageUuid)) {
                throw new RuntimeException('Broken message ID.');
            }

            /*
             * Check is message is valid
             */
            $isValid = $this->messageRepository->checkMessageRead($messageUuid);

            $html = $this->twig->render('Main/status.html.twig', ['messageValid' => $isValid]);
            $response->setContent($html);
        } catch (RuntimeException | LoaderError | RuntimeError | SyntaxError $exception) {
            $error500 = $this->twig->render('Errors/500.html.twig', ['message' => $exception->getMessage()]);
            $response->setContent($error500);
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    public function credits(): Response
    {
        $response = new Response();
        try {
            $html = $this->twig->render('Main/credit.html.twig');
            $response->setContent($html);
        } catch (LoaderError | RuntimeError | SyntaxError $exception) {
            $error500 = $this->twig->render('Errors/500.html.twig', ['message' => $exception->getMessage()]);
            $response->setContent($error500);
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    public function verifyMessage(Request $request): Response
    {
        $response = new Response();

        try {
            $form = $this->createForm(VerifyForm::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $message = $form->getData();
                /*
                 * Check if uuid is valid
                 */
                if (!$this->messageRepository->validateUuid($message['uuid'])) {
                    throw new RuntimeException('Broken message ID.');
                }
                $isValid = $this->messageRepository->checkMessageRead($message['uuid']);


                $html = $this->twig->render('Main/status.html.twig', ['messageValid' => $isValid]);
                $response->setContent($html);
            } else { /* Render default page with form */
                $html = $this->twig->render('Main/verify.html.twig', ['form' => $form->createView()]);
                $response->setContent($html);
            }
        } catch (LoaderError | RuntimeError | SyntaxError | RuntimeException $exception) {
            $error500 = $this->twig->render('Errors/500.html.twig', ['message' => $exception->getMessage()]);
            $response->setContent($error500);
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}