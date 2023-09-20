<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
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
        return new JsonResponse([
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

        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, 400);
        }

        // tell Doctrine you want to (eventually) save the User (no queries yet)
        $em->persist($user);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return new JsonResponse('Saved new user with id ' . $user->getId());
    }

    #[Route('/user/{id}', name: 'user_show')]
    public function getUserbyId(UserRepository $repository, int $id): JsonResponse
    {
        $user = $repository->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id ' . $id
            );
        }

        return new JsonResponse('Check out this great user: ' . $user->getName());

        // Once you have a repository object, you have many helper methods:


        // look for a single Product by its primary key (usually "id")
        $user = $repository->find($id);

        // look for a single Product by name
        $user = $repository->findOneBy(['name' => 'Keyboard']);
        // or find by name and price
        $user = $repository->findOneBy([
            'name' => 'Keyboard',
            'price' => 1999,
        ]);

        // look for multiple Product objects matching the name, ordered by price
        $users = $repository->findBy(
            ['name' => 'Keyboard'],
            ['price' => 'ASC']
        );

        // look for *all* Product objects
        $users = $repository->findAll();
    }
}
