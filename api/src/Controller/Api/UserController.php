<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Message\UserMessage;
use App\Model\Dto\UserDto;
use App\Model\Query\UserQuery;
use App\Repository\UserRepository;
use App\Service\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/users', name: 'users_')]
class UserController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(
        UserRepository                 $userRepository,
        UserManager                    $userManager,
        #[MapQueryString] UserQuery $userQuery = new UserQuery(),
    ): JsonResponse
    {
        $users = $userRepository->getBaseQueryBuilder($userQuery)
            ->getQuery()
            ->getResult();

        $data = [];

        foreach ($users as $user) {
            $data[] = $userManager->entityToDto($user);
        }

        return $this->json(
            $data,
            context: [
                "groups" => [
                    "user:list"
                ]
            ]
        );
    }

    #[Route('/{uuid}', name: 'detail', methods: ['GET'])]
    public function detail(
        User        $customer,
        UserManager $customerManager
    ): JsonResponse
    {
        return $this->json(
            $customerManager->entityToDto($customer),
            context: [
                "groups" => [
                    "user:detail"
                ]
            ]
        );
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload(validationGroups: ["user:create"])] UserDto $customerDto,
        UserManager                                                     $customerManager,
        EntityManagerInterface                                          $em,
        ValidatorInterface  $validator,
        MessageBusInterface $messageBus
    ): JsonResponse
    {
        $customer = $customerManager->dtoToEntity($customerDto);

        $violations = $validator->validate(
            $customer,
            groups: ["user:create"]
        );

        if (count($violations) > 0) {
            return $this->json($violations, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $messageBus->dispatch(
            new UserMessage(
                $customer->getUuid()->toRfc4122(),
                $customer->getEmail(),
                $customer->getFirstName(),
                $customer->getLastName(),
            )
        );

        $em->persist($customer);
        $em->flush();

        return $this->json(
            $customerManager->entityToDto($customer),
            context: [
                "groups" => [
                    "user:detail"
                ]
            ]
        );
    }

    #[Route('/{uuid}', name: 'update', methods: ['PUT'])]
    public function update(
        User                                                            $customer,
        #[MapRequestPayload(validationGroups: ["user:update"])] UserDto $customerDto,
        UserManager                                                     $customerManager,
        EntityManagerInterface                                          $em,
        ValidatorInterface                                              $validator,
        MessageBusInterface $messageBus
    ): JsonResponse
    {
        $customer = $customerManager->dtoToEntity($customerDto, $customer);

        $violations = $validator->validate(
            $customer,
            groups: ["customer:update"]
        );

        if (count($violations) > 0) {
            return $this->json($violations, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $messageBus->dispatch(
            new UserMessage(
                $customer->getUuid()->toRfc4122(),
                $customer->getEmail(),
                $customer->getFirstName(),
                $customer->getLastName(),
            )
        );

        $em->flush();

        return $this->json(
            $customerManager->entityToDto($customer),
            context: [
                "groups" => [
                    "user:detail"
                ]
            ]
        );
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(
        User                   $customer,
        EntityManagerInterface $em
    ): Response
    {
        $em->remove($customer);
        $em->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
