<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    #[Route('/user', name: 'new_user', methods: ["POST"])]
    public function createUser(EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {


        $user = new User();
        $user->setName('');
        $user->setPassword('tata');
        $user->setRole('admin!');

        $errors = $validator->validate($user);
        $errorsString =  $errors;

        if (count($errors) > 0) {
            return new JsonResponse((string) $errorsString, 400);
        }

        // tell Doctrine you want to (eventually) save the User (no queries yet)
        $em->persist($user);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return new JsonResponse('Saved new user with id ' . $user->getId());
    }
}
