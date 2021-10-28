<?php
declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Uam\AggregateRepository\AdminUser;

use Bizlogics\Uam\Aggregate\AdminUser\AccountStatus;
use Bizlogics\Uam\Aggregate\AdminUser\AdminUser;
use Bizlogics\Uam\Aggregate\AdminUserAggregateRepositoryInterface;
use Bizlogics\Uam\Aggregate\Exception\NotExistException;
use Bizlogics\Uam\Aggregate\Exception\PasswordIsNotMatchException;
use Bizlogics\Uam\UseCase\AuthenticatableInterface;

final class ForTestAdminUserAggregateRepository implements AdminUserAggregateRepositoryInterface
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
    {
        $this->save(AdminUser::buildForTestData(
            self::テスト用_管理者ID_1_Enabled,
            self::テスト用_管理者ID_1_Enabledメールアドレス,
            self::テスト用Password,
            AccountStatus::enabled()->raw(),
            null,
            null
        ));
    }

    public static function getInstance(): self
    {
        if (!isset(self::$singleton)) {
            self::$singleton = new self();
        }
        return self::$singleton;
    }

    public function getAdminIdByEmail(string $email): int
    {
        foreach ($this->adminDao as $id => $adminAggregate) {
            if ($adminAggregate->email() === $email) {
                return $id;
            }
        }
        return 0;
    }

    public function findById(int $id): ?AdminUser
    {
        return $this->adminDao[$id] ?? null;
    }

    public function save(AdminUser $adminAggregate): int
    {
        $id = $adminAggregate->id();
        if ($id === 0) {
            $keys = array_keys($this->adminDao);
            sort($keys);
            $id = end($keys) + 1;
            $adminAggregate = AdminUser::buildForTestData(
                $id,
                $adminAggregate->email(),
                $adminAggregate->password(),
                $adminAggregate->accountStatus()
            );
        }
        $this->adminDao[$id] = $adminAggregate;
        return $id;
    }

    /**
     * @inheritDoc
     */
    public function checkAuth(string $email, string $password): AuthenticatableInterface
    {
        $id = $this->getAdminIdByEmail($email);
        if (!array_key_exists($id, $this->adminDao)) {
            throw new NotExistException();
        }
        $adminAggregate = $this->adminDao[$id];
        if ($password !== $adminAggregate->password()) {
            throw new PasswordIsNotMatchException();
        }
        return new class($adminAggregate)
            implements AuthenticatableInterface
            {
                private AdminUser $adminAggregate;
                public function __construct(AdminUser $adminAggregate){ $this->adminAggregate = $adminAggregate; }
                public function getId(): int { return $this->adminAggregate->id(); }
            };
    }
}
