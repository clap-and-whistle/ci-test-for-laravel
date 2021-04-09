<?php
declare(strict_types=1);

namespace Tests\Unit\BizLogics\UseCase\UserOperation\CreateAccount;


use Bizlogics\UseCase\UserOperation\CreateAccount\CreateAccountUseCase;
use Bizlogics\UseCase\UserOperation\CreateAccount\Result;

final class CreateAccountUseCaseTest extends \Tests\BaseTestCase
{
    public function testExecute(): void
    {
        $useCase = new CreateAccountUseCase();
        $this->assertEquals(Result::class, get_class($useCase->execute()));
    }
}