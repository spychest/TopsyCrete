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
        private readonly string $messagePassword
    ) {
    }

    #[Route('/message', name: 'showMessage')]
    public function message(Request $request): RedirectResponse|Response
    {
        if(!$request->request->get('password')) {
            $this->addFlash('error', 'Vous devez indiquer un mot de passe');
            return $this->redirectToRoute('index');
        }

        $givenPassword = $request->request->get('password');

        if($givenPassword !== $this->messagePassword) {
            $this->addFlash('error', 'Mauvais mot de passe');
            return $this->redirectToRoute('index');
        }

        return $this->render('message/showMessage.html.twig', [
        'messages' => $this->messageService->getMessages()
        ]);
    }

    #[Route('/sendMessage', name: 'sendMessage')]
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
        $this->addFlash('success', 'Votre message à bien été ajouté. Merci !');
        return $this->redirectToRoute('index');
    }
}
