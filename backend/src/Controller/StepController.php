<?php

namespace App\Controller;

use App\Entity\Step;
use App\Entity\Question;
use App\Repository\GameRepository;
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


    // #[Route('/step', name: 'app_step')]
    // public function index(): JsonResponse
    // {
    //     return $this->json([
    //         'message' => 'Welcome to your new controller!',
    //         'path' => 'src/Controller/StepController.php',
    //     ]);
    // }
    #[Route('/step', name: 'new_step', methods: ["POST"])]
    public function createStep(
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        Request $request,
        GameRepository $gameRepository,
        QuestionRepository $question,
    ): JsonResponse {

        $parameters = json_decode($request->getContent(), false);
        $questions = [];

        if (!$parameters) {
            throw new HttpException('Invalid parameters');
        }
        if (isset($parameters->questions) && is_array($parameters->questions)) {

            foreach ($parameters->questions as $questionParam) {
                $tempQuestion = new Question();
                $tempQuestion->setDescription($questionParam->description);
                $tempQuestion->setStep($questionParam->step);
                $tempQuestion->setNextStep($questionParam->nextStep);
                $questions[] = $tempQuestion;
            }
        } else {
            throw new \InvalidArgumentException("error with parameter question");
        }

        $game = $gameRepository->find($parameters->gameId);
        if (!$game) {
            throw new \InvalidArgumentException("this game does'nt exist");
        }



        $step = new Step();

        $step->setDescription($parameters->description);
        $step->setStepNumber($parameters->stepNumber);
        foreach($questions as $question){
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
}
