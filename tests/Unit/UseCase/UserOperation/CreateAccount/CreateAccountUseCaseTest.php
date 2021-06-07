<?php
declare(strict_types=1);

namespace Tests\Unit\UseCase\UserOperation\CreateAccount;

use Bizlogics\UseCase\UserOperation\CreateAccount\CreateAccountUseCase;
use Bizlogics\UseCase\UserOperation\CreateAccount\Result;
use Tests\BaseTestCase;

final class CreateAccountUseCaseTest extends BaseTestCase
{
    public function test_基本系列()
    {
        $useCase = new CreateAccountUseCase();
        $result = $useCase->execute();
        self::assertSame(Result::class, get_class($result));
        self::assertTrue($result->isSuccess());
    }
}
