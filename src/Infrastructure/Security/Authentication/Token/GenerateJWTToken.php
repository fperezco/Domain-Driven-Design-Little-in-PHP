<?php
declare(strict_types=1);

namespace App\Infrastructure\Security\Authentication\Token;

use App\Application\Shared\Security\TokenGeneratorInterface;
use App\Application\UseCase\User\LoginUser\LoginTokensDTO;
use App\Domain\User\Entity\User;
use App\Infrastructure\Security\Authentication\Model\AuthUser;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManager;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class GenerateJWTToken implements TokenGeneratorInterface
{
    private JWTTokenManagerInterface $JWTTokenManager;
    private RefreshTokenManager $refreshTokenManager;

    public function __construct(JWTTokenManagerInterface $JWTTokenManager,RefreshTokenManagerInterface $refreshTokenManager)
    {
        $this->JWTTokenManager = $JWTTokenManager;
        $this->refreshTokenManager = $refreshTokenManager;
    }

    public function generateTokensByUser(User $user): LoginTokensDTO
    {
        $token = $this->generateTokenByUser($user);
        $refreshToken = $this->generateRefreshTokenByUser($user);
        $loginTokensDto = new LoginTokensDTO($token,$refreshToken);
        return $loginTokensDto;
    }

    public function generateTokenByUser(User $user): string
    {
        $token = $this->JWTTokenManager->create(new AuthUser($user->getUuid()->value(),
            $user->getUsername()->value(),
            $user->getEmail(),
            $user->getPassword()->value(),
            $user->getRoles()));
        return $token;
    }

    public function generateRefreshTokenByUser(User $user): string
    {
        $refreshToken = $this->refreshTokenManager->create();
        $refreshToken->setUsername($user->getUsername()->value());
        $refreshToken->setRefreshToken();
        $datetime = new \DateTime();
        $datetime->modify('+15 days'); //TODO SET TIME FROM ENV!
        $refreshToken->setValid($datetime);
        $this->refreshTokenManager->save($refreshToken);
        return $refreshToken->getRefreshToken();
    }
}