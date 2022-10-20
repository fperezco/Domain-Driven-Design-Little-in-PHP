<?php
declare(strict_types=1);

namespace App\Tests\Domain\User\Entity;

use App\Domain\Shared\Exception\DomainException;
use App\Domain\User\Entity\UserFirstName;
use PHPUnit\Framework\TestCase;

class UserFirstNameTest extends TestCase
{
    public function test_invalid_name_should_throw_exception()
    {
        //GIVEN
        $name = "";
        //THEN
        $this->expectException(DomainException::class);
        //WHEN
        $name = new UserFirstName($name);
    }

    public function test_invalid_name_min_length_should_throw_exception()
    {
        //GIVEN
        $name = "a";
        //THEN
        $this->expectException(DomainException::class);
        //WHEN
        $name = new UserFirstName($name);
    }

    public function test_invalid_name_max_length_should_throw_exception()
    {
        //GIVEN
        $name = "12345678901234567890123456789012345678901234567890a";
        //THEN
        $this->expectException(DomainException::class);
        //WHEN
        $name = new UserFirstName($name);
    }

    public function test_valid_name_create_value_object(){
        //GIVEN
        $name = "Juanito";
        //WHEN
        $nameObject = new UserFirstName($name);
        //THEN
        $this->assertEquals(get_class($nameObject),UserFirstName::class);
        $this->assertEquals($name,$nameObject->value());
    }
}