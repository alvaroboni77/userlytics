<?php

namespace App\Controller;


use App\Entity\Assistant;
use App\Form\AssistantType;
use App\Service\CheckTicketsLeftService;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AssistantController extends AbstractController
{
    private $checkTicketsLeftService;
    private $mailerService;

    function __construct(CheckTicketsLeftService $checkTicketsLeftService, MailerService $mailerService)
    {
        $this->checkTicketsLeftService = $checkTicketsLeftService;
        $this->mailerService = $mailerService;
    }

    public function new(Request $request): Response
    {
        $assistant = new Assistant();
        $form = $this->createForm(AssistantType::class, $assistant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $assistant = $form->getData();
            $assistant->setCreatedAt(new \DateTimeImmutable());
            $isAvailable = $this->checkTicketsLeftService->isTicketCategoryAvailable($assistant->getTicketCategory());
            if(!$isAvailable){
                return $this->redirectToRoute('assistant_category_full');
            }
            $this->mailerService->sendMail($assistant);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($assistant);
            $entityManager->flush();

            return $this->redirectToRoute('assistant_success');
        }

        return $this->render('assistant/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function success(): Response
    {
        return $this->render('assistant/success.html.twig');
    }

    public function categoryFull(): Response
    {
        return $this->render('assistant/categoryFull.html.twig');
    }
}