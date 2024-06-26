<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController {

    #[Route('/', name: 'home')]
    public function home(EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator): Response {
        
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);
 
        $comment = new Comment();
        $errors = $validator->validate($comment);
        
        
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($form->getData());
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }        

        $comments = $entityManager->getRepository(Comment::class)->findBy([], [
            'id' => 'DESC'
        ]);

        /* return new Response('Welcome, página home ' . $search); */
        return $this->render('home.html.twig', [
            'comments' => $comments,
            'errors' => $errors,
            'form' => $form->createView()
        ]);
    }
}