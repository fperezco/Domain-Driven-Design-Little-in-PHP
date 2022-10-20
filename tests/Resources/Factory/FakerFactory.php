<?php

declare(strict_types=1);

namespace App\Tests\Resources\Factory;

use App\Domain\Shared\ValueObject\Uuid;
use App\Tests\Resources\Factory\FactoryTraits\DeliveryCompanyFactoryTrait;
use App\Tests\Resources\Factory\FactoryTraits\TaskFactoryTrait;
use App\Tests\Resources\Factory\FactoryTraits\UserFactoryTrait;
use Faker\Factory;
use Faker\Generator;

class FakerFactory
{
    use UserFactoryTrait;
    use DeliveryCompanyFactoryTrait;
    use TaskFactoryTrait;


    public ?Generator $faker = null;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function getValidUUid(): Uuid
    {
        return new Uuid(Factory::create()->uuid());
    }


}
