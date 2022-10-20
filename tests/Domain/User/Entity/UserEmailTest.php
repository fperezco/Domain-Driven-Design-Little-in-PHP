<?php
declare(strict_types=1);

namespace App\Tests\Domain\User\Entity;

use App\Domain\Shared\Exception\DomainException;
use App\Domain\User\Entity\UserEmail;
use PHPUnit\Framework\TestCase;

class UserEmailTest extends TestCase
{

    public function test_empty_email_should_throw_exception()
    {
        //GIVEN
        $email = "";
        //THEN
        $this->expectException(DomainException::class);
        //WHEN
        $email = new UserEmail($email);
    }

    public function test_invalid_email_should_throw_exception()
    {
        //GIVEN
        $email = "incorrect@email@.com";
        //THEN
        $this->expectException(DomainException::class);
        //WHEN
        $email = new UserEmail($email);
    }

    public function test_invalid_email_max_length_should_throw_exception()
    {
        //GIVEN
        $email = "12345678901234567890123456789012345678901234@.com";
        //THEN
        $this->expectException(DomainException::class);
        //WHEN
        $email = new UserEmail($email);
    }

    /**
     * @throws DomainException
     */
    public function test_valid_email_create_value_object(){
        //GIVEN
        $email = "correct@4a-side.ninja.com";
        //WHEN
        $emailObject = new UserEmail($email);
        //THEN
        $this->assertEquals(get_class($emailObject),UserEmail::class);
        $this->assertEquals($email,$emailObject->value());
    }
}