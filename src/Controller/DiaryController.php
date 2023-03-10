<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Form\MealType;
use App\Entity\MealUser;
use App\Service\ChartJS;
use App\Form\MealSearchType;
use App\Service\MealCalculator;
use App\Repository\MealRepository;
use App\Repository\MealUserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/meal')]
#[IsGranted('ROLE_USER')]
class DiaryController extends AbstractController
{
    public const MEAL_LIMIT = 50;

    #[Route('', name: 'app_diary')]
    public function index(
        MealUserRepository $mealUserRepo,
        Request $request,
    ): Response {
        /** @var \App\Entity\User */
        $user = $this->getUser();

        $form = $this->createForm(MealSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
        }

        $mealsUser = $mealUserRepo->mealSearch($data['name'] ?? '', $user, $data['type'] ?? '', $data['favourite'] ?? '', $data['origin'] ?? '');

        return $this->render(
            'diary/index.html.twig',
            [
                'mealsUser' => $mealsUser,
                'form' => $form
            ]
        );
    }

    #[Route('/voir/{meal}', name: 'app_meal_show')]
    public function show(
        Meal $meal,
        ChartJS $chartJS,
        MealCalculator $mealCalculator
    ): Response {
        /** @var \App\Entity\User */
        $user = $this->getUser();

        return $this->render(
            'diary/show.html.twig',
            [
                'meal' => $meal,
                'lipidChart' => $chartJS->lipidMealChart($meal),
                'proteinChart' => $chartJS->proteinMealChart($meal),
                'carbChart' => $chartJS->carbMealChart($meal),
                'mealCalories' => $mealCalculator->getMealCalories($meal)

            ]
        );
    }

    #[Route('/nouveau', name: 'app_meal_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        MealRepository $mealRepository,
        MealUserRepository $mealUserRepo,
        MealCalculator $mealCalculator
    ): Response {
        $meal = new Meal();
        $form = $this->createForm(MealType::class, $meal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $meal->setCalories($mealCalculator->getMealCalories($meal));
            $mealRepository->save($meal, true);

            $mealUser = new MealUser();
            $mealUser->setmeal($meal);
            $mealUser->setUser($this->getUser());
            $mealUser->setDate($meal->getDate());
            $mealUser->setIsFavourite($meal->isIsFavourite());

            $mealUserRepo->save($mealUser, true);

            return $this->redirectToRoute('app_diary', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('diary/new.html.twig', [
            'meal' => $meal,
            'form' => $form,
        ]);
    }

    #[Route('/{meal}/modifier', name: 'app_meal_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Meal $meal,
        MealRepository $mealRepository,
        MealUserRepository $mealUserRepo,
        MealCalculator $mealCalculator
    ): Response {
        $form = $this->createForm(MealType::class, $meal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $meal->setCalories($mealCalculator->getMealCalories($meal));
            $mealRepository->save($meal, true);
            $mealUser = $mealUserRepo->findOneBy(['meal' => $meal, 'user' => $this->getUser()]);

            if ($mealUser) {
                $mealUser->setmeal($meal);
                $mealUser->setUser($this->getUser());
                $mealUser->setDate($meal->getDate());
                $mealUser->setIsFavourite($meal->isIsFavourite());
            } else {
                $mealUser = new MealUser();
                $mealUser->setmeal($meal);
                $mealUser->setUser($this->getUser());
                $mealUser->setDate($meal->getDate());
                $mealUser->setIsFavourite($meal->isIsFavourite());
            }
            $mealUserRepo->save($mealUser, true);

            return $this->redirectToRoute('app_meal_show', ['meal' => $meal->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('diary/edit.html.twig', [
            'meal' => $meal,
            'form' => $form,
        ]);
    }

    #[Route('/supprimer/{meal}', name: 'app_meal_delete', methods: ['POST'])]
    public function delete(Request $request, Meal $meal, MealRepository $mealRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $meal->getId(), $request->request->get('_token'))) {
            $mealRepository->remove($meal, true);
        }

        return $this->redirectToRoute('app_diary', [], Response::HTTP_SEE_OTHER);
    }
}
