<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TaskController extends AbstractController
{
    protected TaskRepository $taskRepository;
    #[Route('/task', name: 'app_task')]
    public function index(TaskRepository $taskRepository): Response
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
            'tasks' => $taskRepository->findAll(),
        ]);

    }
    //ajouter Index pour afficher la liste des class
    // Create pour créer une nouvelle tache
    // edit pour modifier une tache
    //delete pour l'éffacer
    //ajouter un template twig pour afficher
    #[Route('/task/{id}', name: 'new_task', methods: ['GET', 'POST'])]
    public function create(Request $request): Response{
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Task created successfully!');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();
            return $this->redirectToRoute('app_task');
        }
        $task->setCreateAt(new \DateTime('now'));

        return $this->render('task/index.html.twig', [
            'form_task' => $form->createView(),
        ]);
    }
}
