<?php
declare(strict_types=1);

namespace Bizlogics\UseCase\UserOperation\CreateAccount;


use Bizlogics\Aggregate\User\Exception\PasswordSizeTooShortException;
use Bizlogics\Aggregate\User\Exception\PasswordTypeCompositionInvalidException;
use Bizlogics\UseCase\UserOperation\CreateAccount\Exception\ApplyingException;
use Bizlogics\UseCase\UserOperation\CreateAccount\Exception\EmailAlreadyUsedException;

final class CreateAccountUseCase
{
    public function execute(): Result
    {
        $result = new Result();
        try {
            $userId = 0;
//            $result->setFailure(new \RuntimeException('hoge'), '');
        } catch (ApplyingException $e) {
            $result->setFailure(new $e, '仮: '. get_class($e));
        } catch (EmailAlreadyUsedException $e) {
            $result->setFailure(new $e, '仮: '. get_class($e));
        } catch (PasswordSizeTooShortException $e) {
            $result->setFailure(new $e, '仮: '. get_class($e));
        } catch (PasswordTypeCompositionInvalidException $e) {
            $result->setFailure(new $e, '仮: '. get_class($e));
        }

        return $result;
    }
}
