<?php
declare(strict_types=1);

namespace Bizlogics\Uam\UseCase\UserOperation\Login;

use Bizlogics\Uam\Aggregate\Exception\NotExistException;
use Bizlogics\Uam\Aggregate\Exception\PasswordIsNotMatchException;
use Bizlogics\Uam\Aggregate\UserAggregateRepositoryInterface;

final class LoginUseCase
{
    private UserAggregateRepositoryInterface $userRepos;

    public const E_MSG_NOT_EXISTS = "入力されたメールアドレスを使用するユーザはいません";
    public const E_MSG_PASSWORD_IS_NOT_MATCH = "パスワードが一致しません";

    /**
     * LoginUseCase constructor.
     * @param UserAggregateRepositoryInterface $userRepos
     */
    public function __construct(UserAggregateRepositoryInterface $userRepos)
    {
        $this->userRepos = $userRepos;
    }

    public function execute(string $email, string $password): Result
    {
        $result = new Result();
        try {
            $authenticatable = $this->userRepos->checkAuth($email, $password);
            $result->setAuthObj($authenticatable);
        } catch (NotExistException $e) {
            $result->setFailure($e, self::E_MSG_NOT_EXISTS);
        } catch (PasswordIsNotMatchException $e) {
            $result->setFailure($e, self::E_MSG_PASSWORD_IS_NOT_MATCH);
        }

        return $result;
    }
}
