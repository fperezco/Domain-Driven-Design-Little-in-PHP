<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\CustomTypes;


use App\Domain\Shared\ValueObject\Uuid;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class UuidType extends StringType
{
    protected function typeClassName(): string
    {
        return Uuid::class;
    }


    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $className = $this->typeClassName();
        return $value ? new $className($value) : null;

    }

    /** @var Uuid $value */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value ? $value->value() : null;
    }
}
