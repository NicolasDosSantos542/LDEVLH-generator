<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Repository\StepRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;



class GameController extends AbstractController
{
    #[Route('/games', name: 'all_games')]
    public function showGames(EntityManagerInterface $em, StepRepository $stepRepository, GameRepository $repository): JsonResponse
    {
        $repository = $em->getRepository(Game::class);
        $records = $repository->findAll();
        $output = [];
        foreach ($records as $record) {
            $output[] = $this->getGamebyId(  $stepRepository,  $repository, $record->getId(), false);
        }

        return new JsonResponse($output);
    }


    #[Route('/game', name: 'create_game', methods: ['POST'])]
    public function createGame(EntityManagerInterface $em, ValidatorInterface $validator, Request $request): JsonResponse
    {

        $parameters = json_decode($request->getContent(), false);
        if (!$parameters) {
            throw new HttpException('Invalid parameters');
        }
        $game = new Game();
        $game->setName($parameters->name);
        $game->setCreatorId($parameters->user_id);

        $errors = $validator->validate($game);

        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, 400);
        }

        $em->persist($game);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return new JsonResponse(['message' => 'Saved new game with id ' . $game->getId(), "data" => $game->getGame()]);
    }

    #[Route('game/{id}', methods: ['PUT'], name: "update_game")]
    public function updateGame(EntityManagerInterface $entityManager, ValidatorInterface $validator, Request $request, int $id)
    {
        $parameters = json_decode($request->getContent(), false);
        if (!$parameters) {
            throw new HttpException('Invalid parameters');
        }
        $game = $entityManager->getRepository(Game::class)->find($id);

        if (!$game) {
            throw $this->createNotFoundException(
                'No game found for id ' . $id
            );
        }
        if (isset($parameters->name)) {
            $game->setName($parameters->name);
        }
        if (isset($parameters->creatorId)) {
            $game->setCreatorId($parameters->creatorId);
        }

        $entityManager->flush();

        return $this->redirectToRoute('game_show', [
            'id' => $game->getId()
        ]);
    }


    #[Route('/game/{id}', name: 'game_show', methods: ['GET'])]
    public function getGamebyId(StepRepository $stepRepository, GameRepository $repository, int $id, $fromRoute=true)
    {
        $gameObject = $repository->find($id);


        if (!$gameObject) {
            throw $this->createNotFoundException(
                'No game found for id ' . $id
            );
        }
        $querysteps = $stepRepository->findBy(['gameId' => $id]);
        $steps = [];
        foreach ($querysteps as $step) {
            $steps[] = $step->getStep();
        }
        $game  = $gameObject->getGame();
        $game["steps"] = $steps;
        if($fromRoute){
            return new JsonResponse($game);
        }else{
            return $game;
        }
    }

    #[Route('/game/{id}', name: 'game_delete', methods: ['DELETE'])]
    public function deleteGame(EntityManagerInterface $entityManager, GameRepository $repository, int $id)
    {
        $game = $repository->find($id);
        if (!$game) {
            throw $this->createNotFoundException(
                'No game found for id ' . $id
            );
        }
        $entityManager->remove($game);
        $entityManager->flush();

        return $this->redirectToRoute('all_games');
    }
}
