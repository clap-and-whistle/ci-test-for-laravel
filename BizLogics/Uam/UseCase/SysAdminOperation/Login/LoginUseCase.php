<?php
declare(strict_types=1);

namespace Bizlogics\Uam\UseCase\SysAdminOperation\Login;

use Bizlogics\Uam\Aggregate\AdminUserAggregateRepositoryInterface;
use Bizlogics\Uam\Aggregate\Exception\NotExistException;
use Bizlogics\Uam\Aggregate\Exception\PasswordIsNotMatchException;

final class LoginUseCase
{
    private AdminUserAggregateRepositoryInterface $adminRepos;

    public const E_MSG_NOT_EXISTS = "入力されたメールアドレスを使用する管理ユーザはいません";
    public const E_MSG_PASSWORD_IS_NOT_MATCH = "パスワードが一致しません";

    /**
     * LoginUseCase constructor.
     * @param AdminUserAggregateRepositoryInterface $adminRepos
     */
    public function __construct(AdminUserAggregateRepositoryInterface $adminRepos)
    {
        $this->adminRepos = $adminRepos;
    }

    public function execute(string $email, string $password): Result
    {
        $result = new Result();
        try {
            $authObj = $this->adminRepos->checkAuth($email, $password);
            $result->setAuthObj($authObj);
        } catch (NotExistException $e) {
            $result->setFailure($e, self::E_MSG_NOT_EXISTS);
        } catch (PasswordIsNotMatchException $e) {
            $result->setFailure($e, self::E_MSG_PASSWORD_IS_NOT_MATCH);
        }

        return $result;
    }
}
