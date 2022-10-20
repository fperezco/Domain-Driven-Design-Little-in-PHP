<?php
declare(strict_types=1);

namespace App\Tests\Application;

use App\Application\Shared\Command\CommandBusInterface;
use App\Application\Shared\Event\EventBusInterface;
use App\Application\Shared\Query\QueryBusInterface;
use App\Tests\Resources\Factory\FakerFactory;
use PHPUnit\Framework\TestCase;

abstract class TestCaseWithFactory extends TestCase
{
    public FakerFactory $fakerFactory;
    public $domainEventPublisher;
    public $queryBus;
    public $commandBus;

    public function setUp(): void
    {
        $this->fakerFactory = new FakerFactory();
        parent::setUp();
    }

    /** @return QueryBusInterface */
    protected function queryBus()
    {
        return $this->createMock(QueryBusInterface::class);
    }

    /** @return EventBusInterface|\PHPUnit\Framework\MockObject\MockObject */
    protected function getEventBusMock()
    {
        return $this->createMock(EventBusInterface::class);
    }

    /** @return CommandBusInterface */
    protected function commandBus()
    {
        return $this->createMock(CommandBusInterface::class);
    }
}