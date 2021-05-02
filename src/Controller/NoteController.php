<?php

namespace Bible\Controller;

use Bible\Entity\Note;
use Bible\Form\NoteType;
use Bible\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NoteController extends AbstractController
{
    #[Route('/note/save/{note}', name: 'bible_note_save', defaults: ['note' => null])]
    public function save(Request $request, EntityManagerInterface $manager, ?Note $note): Response|JsonResponse
    {
        $this->isAllowed($note);
        $form = $this->createForm(NoteType::class, $note, [
            'attr' => [
                'data-codemirror-id' => 'note_'.($note !== null ? $note->getId() : 0),
                'class' => 'ui form'
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Note $note */
            $note = $form->getData();
            $note->setOwner($this->getUser());

            $manager->persist($note);
            $manager->flush();

            return new JsonResponse('bible.note.saved', Response::HTTP_CREATED);
        }

        return $this->render('note/form.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/note/list', name: 'bible_note_list')]
    public function list(NoteRepository $noteRepository): Response
    {
        $notes = $noteRepository->findBy(['owner' => $this->getUser()]);

        return $this->render('note/list.html.twig', ['notes' => $notes]);
    }

    #[Route('/note/delete/{note}', name: 'bible_note_delete')]
    public function delete(Note $note, EntityManagerInterface $manager): JsonResponse
    {
        $this->isAllowed($note);
        $manager->remove($note);
        $manager->flush();

        return new JsonResponse('bible.note.deleted');
    }

    #[Route('/note/{note}', name: 'bible_note')]
    public function note(Note $note): Response
    {
        $this->isAllowed($note);
        return $this->render('note/note.html.twig', ['note' => $note]);
    }

    private function isAllowed(?Note $note): void
    {
        if ($note !== null && $note->getOwner() !== $this->getUser()) {
            throw new RuntimeException('bible.unauthorized', Response::HTTP_UNAUTHORIZED);
        }
    }
}
