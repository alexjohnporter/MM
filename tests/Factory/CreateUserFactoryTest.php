<?php
declare(strict_types=1);

namespace App\Tests\Factory;

use App\Factory\CreateUserFactory;
use App\Message\CreateUser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class CreateUserFactoryTest extends TestCase
{
    public function testCreateMessage(): void
    {
        $factory = new CreateUserFactory();

        $message = $factory->createMessage();

        $this->assertInstanceOf(CreateUser::class, $message);
        $this->assertInstanceOf(Uuid::class, Uuid::fromString($message->getId()));
    }
}
