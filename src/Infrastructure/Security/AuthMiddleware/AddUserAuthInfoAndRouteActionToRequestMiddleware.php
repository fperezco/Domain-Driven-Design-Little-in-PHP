<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\AuthMiddleware;

use App\Application\Shared\Command\CommandBusInterface;
use App\Application\UseCase\User\HasPrivilegesToDo\UserHasPrivilegesCommand;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\Exception\ExceptionsFactory;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class AddUserAuthInfoAndRouteActionToRequestMiddleware
{
    private JWTEncoderInterface $JWTEncoder;
    private CommandBusInterface $commandBus;

    public function __construct(JWTEncoderInterface $JWTEncoder, CommandBusInterface $commandBus)
    {
        $this->JWTEncoder = $JWTEncoder;
        $this->commandBus = $commandBus;
    }

    /**
     * On every api request we check if the token is correct and
     * if it is secured by flag, we add the user uuid to the request
     * to check authorization using UserHasPrivileges
     * @param RequestEvent $event
     * @throws DomainException
     */
    public function onApiRequest(RequestEvent $event): void
    {
        $isThisRouteAuthenticate = $event->getRequest()->attributes->get('auth', false);

        if ($isThisRouteAuthenticate) {
            $this->checkTokenGuard($event->getRequest());
            $usernameUuid = $this->getUserUuidFromAuthRequest($event->getRequest());
            //exception for the logout route where a deleted user could make a logout request due to maybe is deleted in the middle of the session
            if( !($event->getRequest()->attributes->get('_route',true) == 'users_logout'))
            {
                $this->checkPrivilegesForThisUserInThisRouteResource($usernameUuid, $event);
            }
            $this->injectUserUuidToTheRequest($event, $usernameUuid);
        }
    }

    /**
     * @param Request $request
     * @throws DomainException
     */
    private function checkTokenGuard(Request $request): void
    {
        if (!$request->headers->get('Authorization')) {
            throw ExceptionsFactory::authorizationRequired();
        }
        try {
            $token = explode("Bearer ", $request->headers->get('Authorization'))[1];
        } catch (\Exception $e) {
            throw ExceptionsFactory::authorizationRequired();
        }

        if ($token == "") {
            throw ExceptionsFactory::authorizationRequired();
        }

        try {
            //check correct form token and if its expired
            $this->JWTEncoder->decode($token);
        } catch (\Exception $e) {
            throw ExceptionsFactory::tokenError("Token error: " . $e->getMessage());
        }
    }


    private function getUserUuidFromAuthRequest(Request $request): string
    {
        $token = explode("Bearer ", $request->headers->get('Authorization'))[1];
        $tokenPayload = $this->JWTEncoder->decode($token);
        $usernameUuid = $tokenPayload['username'];
        return $usernameUuid;
    }

    private function checkPrivilegesForThisUserInThisRouteResource(string $usernameUuid, RequestEvent $event): void
    {
        $resource = $event->getRequest()->attributes->get('resource', false);
        $resource = explode ("|", $resource);
        $action = $event->getRequest()->attributes->get('action', false);
        $resourceUuid = $event->getRequest()->attributes->get('uuid', null);
        $this->commandBus->dispatch(new UserHasPrivilegesCommand($usernameUuid, $resource, $action,$resourceUuid));
    }

    /**
     * inject the usersUuid in the request for future check/filters. For example just see resources in my departments
     * @param RequestEvent $event
     * @param string $usernameUuid
     */
    private function injectUserUuidToTheRequest(RequestEvent $event, string $usernameUuid): void
    {
        $event->getRequest()->query->add(['userUuid' => $usernameUuid]);
    }


}
