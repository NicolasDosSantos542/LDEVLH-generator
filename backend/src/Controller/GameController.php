<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
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
    public function showGames(EntityManagerInterface $em,): JsonResponse
    {
        $repository = $em->getRepository(Game::class);
        $records = $repository->findAll();
        $output = [];
        foreach ($records as $record) {
            $output[] = $record->getGame();
        }

        return new JsonResponse($output);
    }


    #[Route('/game', name: 'create_game', methods: ['POST'])]
    public function createGame(EntityManagerInterface $em, ValidatorInterface $validator, Request $request): JsonResponse
    {
       
        $parameters = json_decode($request->getContent(), false);
        if(!$parameters){
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

        return new JsonResponse(['message' => 'Saved new game with id ' . $game->getId(), "data" => $game]);
    }

    #[Route('game/{id}', methods:['PUT'], name : "update_game")]
    public function updateGame(EntityManagerInterface $em, ValidatorInterface $validator, Request $request){
        $parameters = json_decode($request->getContent(), false);
        if(!$parameters){
            throw new HttpException('Invalid parameters');
        }

    }
}
