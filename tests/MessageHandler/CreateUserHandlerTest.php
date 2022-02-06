<?php
declare(strict_types=1);

namespace App\Tests\MessageHandler;

use App\Entity\User;
use App\Message\CreateUser;
use App\MessageHandler\CreateUserHandler;
use App\Model\Coordinates;
use App\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Uid\Uuid;

class CreateUserHandlerTest extends TestCase
{
    use ProphecyTrait;

    private UserRepositoryInterface | ObjectProphecy $userRepository;
    private CreateUserHandler $handler;

    protected function setUp(): void
    {
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
        $this->handler = new CreateUserHandler(
            $this->userRepository->reveal()
        );
    }

    public function testItCreatesAUser(): void
    {
        $message = new CreateUser(
            (string)Uuid::v4(),
            'foo@bar.com',
            'foobar',
            'name',
            'm',
            30,
            new Coordinates(0, 0)
        );

        $this->userRepository->save(Argument::type(User::class))->shouldBeCalled();

        $user = $this->handler->__invoke($message);

        $this->assertEquals($message->getId(), $user->getId());
        $this->assertEquals($message->getEmail(), $user->getEmail());
        $this->assertEquals($message->getPassword(), $user->getPassword());
        $this->assertEquals($message->getGender(), $user->getGender());
        $this->assertEquals($message->getAge(), $user->getAge());
        $this->assertEquals($message->getCoordinates(), $user->getCoordinates());
    }
}
