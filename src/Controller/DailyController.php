<?php

declare(strict_types=1);

namespace Bible\Controller;

use Bible\Entity\Daily;
use Bible\Form\DailyType;
use Bible\Repository\DailyRepository;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DailyController extends AbstractController
{
    #[Route('/daily/save/{daily}', name: 'bible_daily_save', defaults: ['daily' => null])]
    public function save(
        Request $request,
        EntityManagerInterface $manager,
        DailyRepository $dailyRepository,
        ?Daily $daily
    ): Response|JsonResponse
    {
        $this->isAllowed($daily);
        if ($daily === null && $dailyRepository->existsForCurrentDay()) {
            return new JsonResponse('bible.daily.exist_for_current_day');
        }

        $form = $this->createForm(DailyType::class, $daily, ['attr' => ['class' => 'ui form']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Daily $daily */
            $daily = $form->getData();
            $daily->setOwner($this->getUser());

            $manager->persist($daily);
            $manager->flush();

            return new JsonResponse('bible.daily.saved', Response::HTTP_CREATED);
        }

        return $this->render('daily/form.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/daily/list', name: 'bible_daily_list')]
    public function list(DailyRepository $dailyRepository): Response
    {
        $dailies = $dailyRepository->findBy(['owner' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('daily/list.html.twig', ['dailies' => $dailies]);
    }

    #[Route('/daily/delete/{daily}', name: 'bible_daily_delete')]
    public function delete(Daily $daily, EntityManagerInterface $manager): JsonResponse
    {
        $this->isAllowed($daily);
        $manager->remove($daily);
        $manager->flush();

        return new JsonResponse('bible.daily.deleted');
    }

    private function isAllowed(?Daily $daily): void
    {
        if ($daily !== null && $daily->getOwner() !== $this->getUser()) {
            throw new RuntimeException('bible.unauthorized', Response::HTTP_UNAUTHORIZED);
        }
    }
}
