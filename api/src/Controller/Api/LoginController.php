<?php

namespace App\Controller\Api;

use App\Entity\AccessToken;
use App\Entity\User;
use App\Service\JwtService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login', methods: ['POST'])]
    public function index(
        #[CurrentUser] ?User $customer,
        JwtService             $jwtService,
        EntityManagerInterface $em
    ): JsonResponse
    {
        if (null === $customer) {
            return $this->json(
                [
                    "message" => "missing creadentials",
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $iat = new \DateTimeImmutable();
        $payload = [
            "firstName" => $customer->getFirstName(),
            "lastName" => $customer->getLastName(),
            "email" => $customer->getEmail(),
            "iat" => $iat
        ];

        $token = $jwtService->createToken($payload);

        $accessToken = new AccessToken();
        $accessToken->setToken($token);
        $accessToken->setIssuedAt($iat);

        $customer->addAccessToken($accessToken);

        $em->flush();

        return $this->json($token);
    }
}
