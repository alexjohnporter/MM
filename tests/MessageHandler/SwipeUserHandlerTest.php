<?php
declare(strict_types=1);

namespace App\Tests\MessageHandler;

use App\Exception\UserAlreadySwipedException;
use App\Message\SwipeUser;
use App\MessageHandler\SwipeUserHandler;
use App\Repository\UserSwipeRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Uid\Uuid;

class SwipeUserHandlerTest extends TestCase
{
    use ProphecyTrait;

    private UserSwipeRepositoryInterface | ObjectProphecy $userSwipeRepository;
    private SwipeUserHandler $handler;

    protected function setUp(): void
    {
        $this->userSwipeRepository = $this->prophesize(UserSwipeRepositoryInterface::class);
        $this->handler = new SwipeUserHandler(
            $this->userSwipeRepository->reveal()
        );
    }

    public function testItSwipesUser(): void
    {
        $message = new SwipeUser(
            (string) Uuid::v4(),
            (string) Uuid::v4(),
            1
        );

        $this->userSwipeRepository->haveUsersPreviouslySwiped(
            $message->getLoggedInUser(),
            $message->getSwipedUser()
        )->shouldBeCalled()->willReturn(false);

        $this->userSwipeRepository->save(
            Argument::type('string'),
            $message->getLoggedInUser(),
            $message->getSwipedUser(),
            $message->isAttracted(),
            Argument::type(\DateTime::class)
        )->shouldBeCalled();

        $this->handler->__invoke($message);
    }

    public function testItThrowsExceptionIfUsersHaveAlreadySwiped(): void
    {
        $message = new SwipeUser(
            (string) Uuid::v4(),
            (string) Uuid::v4(),
            1
        );

        $this->userSwipeRepository->haveUsersPreviouslySwiped(
            $message->getLoggedInUser(),
            $message->getSwipedUser()
        )->shouldBeCalled()->willReturn(true);

        $this->expectException(UserAlreadySwipedException::class);
        $this->handler->__invoke($message);
    }
}
