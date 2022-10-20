<?php
declare(strict_types=1);

namespace App\Infrastructure\Security\Authentication\Token;

use App\Application\Shared\Security\RefreshTokenManagerInterfaceCustom;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\Exception\ExceptionsFactory;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;

class RefreshTokenManagerCustom implements RefreshTokenManagerInterfaceCustom
{
    private RefreshTokenManagerInterface $refreshTokenManager;

    public function __construct(RefreshTokenManagerInterface $refreshTokenManager)
    {
        $this->refreshTokenManager = $refreshTokenManager;
    }

    /**
     * @throws DomainException
     */
    public function checkIfRefreshTokenIsValid(string $refreshToken): void
    {
        $refreshToken = $this->refreshTokenManager->get($refreshToken);
        if(!$refreshToken) {
            throw ExceptionsFactory::invalidRefreshToken();
        }
        if($refreshToken->getValid() < new \DateTime())
        {
            throw ExceptionsFactory::invalidRefreshTokenExpired();
        }
    }

    public function getUsernameFromRefreshToken(string $refreshToken): string
    {
        $refreshToken = $this->refreshTokenManager->get($refreshToken);
        return $refreshToken->getUsername();
    }


}