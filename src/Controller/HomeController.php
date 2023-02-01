<?php

namespace App\Controller;

use App\Entity\Need;
use DateTime;
use App\Entity\WeightHistory;
use App\Form\UserInformationsType;
use App\Repository\NeedRepository;
use App\Repository\UserRepository;
use App\Service\ChartJS;
use App\Service\NeedsCalculator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        Request $request,
        UserRepository $userRepository,
        NeedsCalculator $needsCalculator,
        NeedRepository $needRepository,
        ChartJS $chartJS
    ): Response {
        /** @var \App\Entity\User */
        $user = $this->getUser();

        $form = $this->createForm(UserInformationsType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $weight = new WeightHistory;
            $today = new DateTime('today');

            /** @var float */
            $tempWeight = $form->getData()->getTempWeight();

            $weight->setUser($user);
            $weight->setWeight($tempWeight);
            $weight->setDate($today);

            $user->addWeight($weight);
            $userRepository->save($user, true);

            $need = new Need;
            $need->setUser($user);
            $need->setMaintenanceCalory($needsCalculator->getMaintenanceCalories($user));
            if ($form->getData()->getGoal() === 'gain') {
                $need->setGainCalory($needsCalculator->getGoalCalories($user));
            } elseif ($form->getData()->getGoal() === 'lean') {
                $need->setLossCalory($needsCalculator->getGoalCalories($user));
            }
            $need->setLipid($needsCalculator->getLipidRepartition($user));
            $need->setProtein($needsCalculator->getProteinRepartition($user));
            $need->setCarb($needsCalculator->getCarbsRepartiton($user));
            $needRepository->save($need, true);

            return $this->redirectToRoute('app_home');
        }



        return $this->render('home/index.html.twig', [
            'form' => $form,
            'proteinChart' => $chartJS->proteinChart($user),
            'lipidChart' => $chartJS->lipidChart($user),
            'carbChart' => $chartJS->carbChart($user)
        ]);
    }
}
