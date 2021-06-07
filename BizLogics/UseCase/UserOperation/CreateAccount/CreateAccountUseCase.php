<?php
declare(strict_types=1);

namespace Bizlogics\UseCase\UserOperation\CreateAccount;


final class CreateAccountUseCase
{
    public function execute(): Result
    {
        $result = new Result();

        $result->setFailure(new \RuntimeException('hoge'), '');
        return $result;
    }
}
