<?php
declare(strict_types=1);

namespace App\Tests\Domain\Shared\ValueObject;

use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

class UuidTest extends TestCase
{
    public function test_invalid_uuid_should_throw_exception()
    {
        //GIVEN
        $uuid = "asfewafsdfasf";
        //THEN
        $this->expectException(DomainException::class);
        //WHEN
        $uuidObj = new Uuid($uuid);
    }

    public function test_valid_uuid_will_be_set()
    {
        //GIVEN
        $uuid = "20000000-c022-c222-c222-000000000000";
        //WHEN
        $uuidObj = new Uuid($uuid);
        //THEN
        $this->assertEquals($uuid,$uuidObj->value());
    }

}