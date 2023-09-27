<?php

namespace App\Controller;

use App\Entity\Step;
use App\Entity\Question;
use App\Repository\GameRepository;
use App\Repository\StepRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StepController extends AbstractController
{




    #[Route('/step', name: 'new_step', methods: ["POST"])]
    public function createStep(
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        Request $request,
        GameRepository $gameRepository,
        QuestionRepository $question,
    ): JsonResponse {

        $step = new Step();

        $parameters = json_decode($request->getContent(), false);
        $questions = [];

        if (!$parameters) {
            throw new HttpException('Invalid parameters');
        }
        if (isset($parameters->questions) && is_array($parameters->questions)) {

            foreach ($parameters->questions as $questionParam) {
                $tempQuestion = new Question();
                $tempQuestion->setDescription($questionParam->description);
                $tempQuestion->setStep($step);
                $tempQuestion->setNextStep($questionParam->nextStep);
                $em->persist($tempQuestion);
                $questions[] = $tempQuestion;
            }
        } else {
            throw new \InvalidArgumentException("error with parameter question");
        }

        $game = $gameRepository->find($parameters->gameId);
        if (!$game) {
            throw new \InvalidArgumentException("this game does'nt exist");
        }




        $step->setDescription($parameters->description);
        $step->setStepNumber($parameters->stepNumber);
        $step->setGameId($game);
        foreach ($questions as $question) {
            $step->addQuestion($question);
        }

        $errors = $validator->validate($step);


        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, 400);
        }

        // tell Doctrine you want to (eventually) save the Step (no queries yet)
        $em->persist($step);


        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return new JsonResponse('Saved new step with id ' . $step->getId());
    }

    #[Route('/step/{id}', name: 'step_show', methods: ['GET'])]
    public function getStepbyId(StepRepository $repository, int $id): JsonResponse
    {
        $step = $repository->find($id);

        if (!$step) {
            throw $this->createNotFoundException(
                'No step found for id ' . $id
            );
        }

        return new JsonResponse($step->getStep());
    }


    #[Route('/step/{id}', name: 'step_delete', methods: ['DELETE'])]
    public function deleteStep(EntityManagerInterface $entityManager, StepRepository $repository, int $id)
    {
        $step = $repository->find($id);
        if (!$step) {
            throw $this->createNotFoundException(
                'No step found for id ' . $id
            );
        }
        $entityManager->remove($step);
        $entityManager->flush();

        return $this->redirectToRoute('all_steps');
    }

    #[Route('/steps', name: 'all_steps')]
    public function showSteps(EntityManagerInterface $em,): JsonResponse
    {
        $repository = $em->getRepository(Step::class);
        $records = $repository->findAll();
        $output = [];
        foreach ($records as $record) {
            $output[] = $record->getStep();
        }

        return new JsonResponse($output);
    }

}
