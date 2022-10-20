<?php
declare(strict_types=1);

namespace App\Tests\Domain\User\Entity;

use App\Domain\Shared\Exception\DomainException;
use App\Domain\User\Entity\UserLastName;
use PHPUnit\Framework\TestCase;

class UserLastNameTest extends TestCase
{
    public function test_invalid_lastName_should_throw_exception()
    {
        //GIVEN
        $lastName = "";
        //THEN
        $this->expectException(DomainException::class);
        //WHEN
        $lastName = new UserLastName($lastName);
    }

    public function test_invalid_lastName_min_length_should_throw_exception()
    {
        //GIVEN
        $lastName = "a";
        //THEN
        $this->expectException(DomainException::class);
        //WHEN
        $lastName = new UserLastName($lastName);
    }

    public function test_invalid_lastName_max_length_should_throw_exception()
    {
        //GIVEN
        $lastName = "12345678901234567890123456789012345678901234567890a";
        //THEN
        $this->expectException(DomainException::class);
        //WHEN
        $lastName = new UserLastName($lastName);
    }

    public function test_valid_lastName_create_value_object(){
        //GIVEN
        $lastName = "Juanito";
        //WHEN
        $lastNameObject = new UserLastName($lastName);
        //THEN
        $this->assertEquals(get_class($lastNameObject),UserLastName::class);
        $this->assertEquals($lastName,$lastNameObject->value());
    }
}