<?php
declare(strict_types=1);

namespace App\Tests\Domain\Shared;

use App\Domain\Shared\Utils;
use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase
{
    /** @test */
    public function generate_random_token_return_a_token_of_50_characters_length(){
        $token = Utils::generateRandomToken();
        $this->assertEquals(50,strlen($token));
        $this->assertTrue(is_string($token));
    }
}