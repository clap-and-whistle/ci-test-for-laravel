<?php
declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Uam\AggregateRepository\AdminUser;

use Bizlogics\Uam\Aggregate\AdminUser\AdminUser;
use Bizlogics\Uam\Aggregate\AdminUserAggregateRepositoryInterface;
use Bizlogics\Uam\Aggregate\Exception\NotExistException;
use Bizlogics\Uam\Aggregate\Exception\PasswordIsNotMatchException;
use Bizlogics\Uam\UseCase\AuthenticatableInterface;

class ForTestAdminUserAggregateRepository implements AdminUserAggregateRepositoryInterface
{
    /** @var AdminUser[]  */
    private array $adminDao = [];

    const テスト用Password = "hogeFuga123";
    const テスト用の誤ったPassword = "hogePiyo123";

    const テスト用_管理者ID_1_Enabled = 1;
    const テスト用_管理者ID_1_Enabledメールアドレス = "admin-enabled@example.local";

    const システム管理コンソールログイン時例外用_使用されていないメールアドレス = "not-used@example.local";

    /** @var self */
    private static $singleton;

    /**
     * ForTestAdminAggregateRepository constructor.
     */
    private function __construct()
    {}

    public static function getInstance(): self
    {
        if (!isset(self::$singleton)) {
            self::$singleton = new self();
        }
        return self::$singleton;
    }

    public function findById(): AdminUser
    {
        // TODO: Implement findById() method.
        return new AdminUser();
    }

    /**
     * @inheritDoc
     */
    public function checkAuth(string $email, string $password): AuthenticatableInterface
    {
        // TODO: Implement checkAuth() method.
        return new class implements AuthenticatableInterface {

            public function getId(): int
            {
                return 0;
            }
        };
    }
}
