<?php
declare(strict_types=1);

namespace App\Tests\Factory;

use App\Exception\UnknownParameterException;
use App\Exception\UserDoesNotExistException;
use App\Factory\SwipeUserFactory;
use App\Message\SwipeUser;
use App\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Uid\Uuid;

class SwipeUserFactoryTest extends TestCase
{
    use ProphecyTrait;

    private UserRepositoryInterface | ObjectProphecy $userRepository;

    private SwipeUserFactory $swipeUserFactory;

    protected function setUp(): void
    {
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
        $this->swipeUserFactory = new SwipeUserFactory(
            $this->userRepository->reveal()
        );
    }

    public function testItCreatesAYesSwipe(): void
    {
        $loggedInUser = (string)Uuid::v4();
        $swipedUser = (string)Uuid::v4();

        $this->userRepository->doesUserExist($loggedInUser)->shouldBeCalled()->willReturn(true);
        $this->userRepository->doesUserExist($swipedUser)->shouldBeCalled()->willReturn(true);

        $message = $this->swipeUserFactory->createMessage(
            $loggedInUser,
            $swipedUser,
            "yes"
        );

        $this->assertInstanceOf(SwipeUser::class, $message);
        $this->assertEquals(1, $message->isAttracted());
    }

    public function testItCreatesANoSwipe(): void
    {
        $loggedInUser = (string)Uuid::v4();
        $swipedUser = (string)Uuid::v4();

        $this->userRepository->doesUserExist($loggedInUser)->shouldBeCalled()->willReturn(true);
        $this->userRepository->doesUserExist($swipedUser)->shouldBeCalled()->willReturn(true);

        $message = $this->swipeUserFactory->createMessage(
            $loggedInUser,
            $swipedUser,
            "no"
        );

        $this->assertInstanceOf(SwipeUser::class, $message);
        $this->assertEquals(0, $message->isAttracted());
    }


    public function testItThrowsAnExceptionForInvalidUser(): void
    {
        $loggedInUser = (string)Uuid::v4();
        $swipedUser = (string)Uuid::v4();

        $this->userRepository->doesUserExist($loggedInUser)->shouldBeCalled()->willReturn(true);
        $this->userRepository->doesUserExist($swipedUser)->shouldBeCalled()->willReturn(false);

        $this->expectException(UserDoesNotExistException::class);

        $this->swipeUserFactory->createMessage(
            $loggedInUser,
            $swipedUser,
            "no"
        );
    }

    public function testItThrowsAnExceptionForInvalidSwipe(): void
    {
        $loggedInUser = (string)Uuid::v4();
        $swipedUser = (string)Uuid::v4();

        $this->userRepository->doesUserExist($loggedInUser)->shouldBeCalled()->willReturn(true);
        $this->userRepository->doesUserExist($swipedUser)->shouldBeCalled()->willReturn(true);

        $this->expectException(UnknownParameterException::class);

        $this->swipeUserFactory->createMessage(
            $loggedInUser,
            $swipedUser,
            "hello"
        );
    }
}
