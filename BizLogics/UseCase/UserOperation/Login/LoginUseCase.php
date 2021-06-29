<?php
declare(strict_types=1);

namespace Bizlogics\UseCase\UserOperation\Login;

use App\Infrastructure\AggregateRepository\User\Exception\NotExistException;
use App\Infrastructure\AggregateRepository\User\Exception\PasswordIsNotMatchException;
use Bizlogics\Aggregate\UserAggregateRepositoryInterface;
use http\Exception\RuntimeException;
use Throwable;

final class LoginUseCase
{
    private UserAggregateRepositoryInterface $userRepos;

    const E_MSG_NOT_EXISTS = "入力されたメールアドレスを使用するユーザはいません";
    const E_MSG_PASSWORD_IS_NOT_MATCH = "パスワードが一致しません";

    /**
     * LoginUseCase constructor.
     * @param UserAggregateRepositoryInterface $userRepos
     */
    public function __construct(UserAggregateRepositoryInterface $userRepos)
    {
        $this->userRepos = $userRepos;
    }

    public function execute(string $email, string $password)
    {
        $result = new Result();
        try {
            $authenticatable = $this->userRepos->checkAuth($email, $password);

            // User集約インスタンスを構築できる状態であることを確認することもこのユースケースの責務の一つなので、
            // 下記の正常終了だけ確認する
            if (!$this->userRepos->findById($authenticatable->getId())) {
                throw new RuntimeException('集約インスタンスの構築に失敗しました');
            }

            $result->setAuthObj($authenticatable);
        } catch (NotExistException $e) {
            $result->setFailure($e, self::E_MSG_NOT_EXISTS);
        } catch (PasswordIsNotMatchException $e) {
            $result->setFailure($e, self::E_MSG_PASSWORD_IS_NOT_MATCH);
        }

        return $result;
    }
}
