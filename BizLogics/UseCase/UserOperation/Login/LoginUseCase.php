<?php
declare(strict_types=1);

namespace Bizlogics\UseCase\UserOperation\Login;

use Bizlogics\Aggregate\UserAggregateRepositoryInterface;

final class LoginUseCase
{
    private UserAggregateRepositoryInterface $userRepos;

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
            // TODO: Unitテストを書いてから適正化するので、ひとまず暫定例外を投げるだけ
            throw new \RuntimeException();
//            $authenticatable = $this->userRepos->checkAuth($email, $password);

            // User集約インスタンスを構築できる状態であることを確認することもこのユースケースの責務の一つなので、
            // 下記の正常終了だけ確認する
//            $this->userRepos->findById($authenticatable->getId());

//            $result->setAuthObj($authenticatable);
        } catch (\RuntimeException $e) {
            $result->setFailure($e, '仮実装');
        }

        return $result;
    }
}
