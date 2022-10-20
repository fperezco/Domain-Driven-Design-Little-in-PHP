<?php
declare(strict_types=1);

namespace App\Tests\Domain\User\Entity;

use App\Domain\Shared\Exception\DomainException;
use App\Domain\User\Entity\UserUsername;
use PHPUnit\Framework\TestCase;

class UserUsernameTest extends TestCase
{
    public function test_invalid_Username_should_throw_exception()
    {
        //GIVEN
        $userName = "";
        //THEN
        $this->expectException(DomainException::class);
        //WHEN
        $userName = new UserUsername($userName);
    }

    public function test_invalid_Username_min_length_should_throw_exception()
    {
        //GIVEN
        $userName = "aefad";
        //THEN
        $this->expectException(DomainException::class);
        //WHEN
        $userName = new UserUsername($userName);
    }

    public function test_invalid_Username_max_length_should_throw_exception()
    {
        //GIVEN
        $userName = "12345678901234567890123456789012345678901234567890a";
        //THEN
        $this->expectException(DomainException::class);
        //WHEN
        $userName = new UserUsername($userName);
    }

    /**
     * @throws DomainException
     */
    public function test_valid_Username_create_value_object(){
        //GIVEN
        $userName = "JuanitoXD";
        //WHEN
        $userNameObject = new UserUsername($userName);
        //THEN
        $this->assertEquals(get_class($userNameObject),UserUsername::class);
        $this->assertEquals($userName,$userNameObject->value());
    }
}