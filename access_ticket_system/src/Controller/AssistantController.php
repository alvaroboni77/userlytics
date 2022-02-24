<?php

namespace App\Controller;


use App\Entity\Assistant;
use App\Form\AssistantType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AssistantController extends AbstractController
{
    public function new(Request $request): Response
    {
        $assistant = new Assistant();
        $form = $this->createForm(AssistantType::class, $assistant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $assistant = $form->getData();
            $assistant->setCreatedAt(new \DateTimeImmutable());
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
}