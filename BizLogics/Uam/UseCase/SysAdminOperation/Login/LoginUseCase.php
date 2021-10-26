<?php
declare(strict_types=1);

namespace Bizlogics\Uam\UseCase\SysAdminOperation\Login;

use Bizlogics\Uam\Aggregate\AdminUserAggregateRepositoryInterface;

final class LoginUseCase
{
    private AdminUserAggregateRepositoryInterface $adminRepos;

    public const E_MSG_NOT_EXISTS = "入力されたメールアドレスを使用するユーザはいません";
    public const E_MSG_PASSWORD_IS_NOT_MATCH = "パスワードが一致しません";

    /**
     * @param AdminUserAggregateRepositoryInterface $adminRepos
     */
    public function __construct(AdminUserAggregateRepositoryInterface $adminRepos)
    {
        $this->adminRepos = $adminRepos;
    }

    public function execute(string $email, string $password): Result
    {
        return new Result();
    }
}
