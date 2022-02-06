<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\User;
use App\Exception\InvalidPasswordException;
use App\Repository\UserRepositoryInterface;
use App\Service\AuthenticationService;
use App\Util\TokenGeneratorInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class AuthenticationServiceTest extends TestCase
{
    use ProphecyTrait;

    private TokenGeneratorInterface|ObjectProphecy $tokenGenerator;
    private UserRepositoryInterface|ObjectProphecy $userRepository;
    private AuthenticationService $service;

    protected function setUp(): void
    {
        $this->tokenGenerator = $this->prophesize(TokenGeneratorInterface::class);
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);

        $this->service = new AuthenticationService(
            $this->userRepository->reveal(),
            $this->tokenGenerator->reveal()
        );
    }

    public function testIsUserAuthenticated(): void
    {
        $hashedPassword = password_hash('foobar', PASSWORD_DEFAULT);
        $user = new User('abc', 'foo@bar.com', $hashedPassword, 'foo', 'm', 18, 0, 0);

        $this->userRepository->getUserByEmail('foo@bar.com')->shouldBeCalled()->willReturn($user);

        $this->tokenGenerator->generateToken()->shouldBeCalled()->willReturn('iamatoken');

        $this->userRepository->save(Argument::type(User::class))->shouldBeCalled();

        $this->service->authenticateUser('foo@bar.com', 'foobar');
    }

    public function testItThrowsExceptionForInvalidPassword(): void
    {
        $hashedPassword = password_hash('foobar', PASSWORD_DEFAULT);
        $user = new User('abc', 'foo@bar.com', $hashedPassword, 'foo', 'm', 18, 0, 0);

        $this->userRepository->getUserByEmail('foo@bar.com')->shouldBeCalled()->willReturn($user);

        $this->expectException(InvalidPasswordException::class);

        $this->service->authenticateUser('foo@bar.com', 'barfoo');
    }
}
