<?php

namespace App\Controller;

use App\Factory\MessageFactory;
use App\Service\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MessageController extends AbstractController
{
    public function __construct(
        private readonly MessageFactory $messageFactory,
        private readonly MessageService $messageService,
    ) {
    }

    #[Route('/message', name: 'sendMessage')]
    public function sendMessage(): Response
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }

    #[Route('/registerMessage', name: 'registerMessage')]
    public function registerMessage(Request $request): RedirectResponse
    {
        try {
            $this->messageService->registerMessage($this->messageFactory->createMessage($request->request->all()));
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('sendMessage');
        }

        return $this->redirectToRoute('index');
    }
}
