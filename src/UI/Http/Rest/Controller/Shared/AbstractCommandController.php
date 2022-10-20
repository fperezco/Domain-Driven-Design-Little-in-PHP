<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Shared;

use App\Application\Shared\Command\CommandBusInterface;
use App\Application\Shared\Command\CommandInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractCommandController
{
    private CommandBusInterface $commandBus;
    protected Request $request;

    public function __construct(
        CommandBusInterface $commandBus,
        RequestStack $requestStack
    ) {
        $this->commandBus = $commandBus;
        $this->request = $requestStack->getCurrentRequest();
    }

    protected function dispatch(CommandInterface $command)
    {
        return $this->commandBus->dispatch($command);
    }
}
