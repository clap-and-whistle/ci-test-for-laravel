<?php
declare(strict_types=1);

namespace App\Infrastructure\AggregateRepository\User;

use App\Infrastructure\AggregateRepository\User\Exception\NotExistException;
use App\Infrastructure\AggregateRepository\User\Exception\PasswordIsNotMatchException;
use Bizlogics\Aggregate\User\User;
use Bizlogics\Aggregate\User\AccountStatus;
use Bizlogics\Aggregate\UserAggregateRepositoryInterface;
use Bizlogics\UseCase\UserOperation\Login\AuthenticatableInterface;
use Exception;

final class ForTestUserAggregateRepository implements UserAggregateRepositoryInterface
{
    /** @var User[] */
    private array $userDao = [];

    const テスト用Password = "hogeFuga123";
    const テスト用の誤ったPassword = "hogePiyo123";

    const 例外用_ユーザID_1_申請中 = 1;
    const 例外用_ユーザID_1_申請中メールアドレス = "appraying@example.local";

    const 例外用_ユーザID_2_既に使用中 = 2;
    const 例外用_ユーザID_2_既に使用されているメールアドレス = "used@example.local";

    const システムログイン時例外用_使用されていないメールアドレス = "not-used@example.local";

    /** @var self */
    private static $singleton;

    /**
     * ForTestUserAggregateRepository constructor.
     */
    private function __construct()
    {
        $this->save(User::buildForTestData(
            self::例外用_ユーザID_1_申請中,
            self::例外用_ユーザID_1_申請中メールアドレス,
            self::テスト用Password,
            AccountStatus::applying()->raw()
        ));
        $this->save(User::buildForTestData(
            self::例外用_ユーザID_2_既に使用中,
            self::例外用_ユーザID_2_既に使用されているメールアドレス,
            self::テスト用Password,
            AccountStatus::inOperation()->raw()
        ));
    }

    public static function getInstance(): self
    {
        if (!isset(self::$singleton)) {
            self::$singleton = new self();
        }
        return self::$singleton;
    }

    public function getUserIdByEmail(string $email): int
    {
        foreach ($this->userDao as $id => $userAggregate) {
            if ($userAggregate->email() === $email) {
                return $id;
            }
        }
        return 0;
    }

    public function save(User $userAggregate): int
    {
        $id = $userAggregate->id();
        if ($id === 0) {
            $keys = array_keys($this->userDao);
            sort($keys);
            $id = end($keys) + 1;
            $userAggregate = User::buildForTestData(
                $id,
                $userAggregate->email(),
                $userAggregate->password(),
                $userAggregate->accountStatus()
            );
        }
        $this->userDao[$id] = $userAggregate;
        return $id;
    }

    public function isApplying(int $userId): bool
    {
        if (array_key_exists($userId, $this->userDao)) {
            return $this->userDao[$userId]->isApplying();
        }
        return false;
    }

    public function findById(int $userId): ?User
    {
        return $this->userDao[$userId] ?? null;
    }

    /**
     * @noinspection NonAsciiCharacters
     */
    public function 使われていないメールアドレスを生成する(): string
    {
        $emailDomain = "example.local";

        do {
            try {
                $emailLocal = $this->ランダムな20桁の文字列を生成する();
            } catch (Exception $e) {
                return "";
            }
        } while ($this->getUserIdByEmail($emailLocal . "@" . $emailDomain) > 0);
        return $emailLocal . "@" . $emailDomain;
    }

    /**
     * @noinspection NonAsciiCharacters
     * @throws Exception
     */
    private function ランダムな20桁の文字列を生成する(): string
    {
        $length = 20;
        $str = array_merge(range('a', 'z'), range('0', '9'));
        $r_str = null;
        for ($i = 0; $i < $length; $i++) {
            $r_str .= $str[random_int(0, count($str) - 1)];
        }
        return $r_str;
    }

    public function checkAuth(string $email, string $password): AuthenticatableInterface
    {
        $userId = $this->getUserIdByEmail($email);
        if (!array_key_exists($userId, $this->userDao)) {
            throw new NotExistException();
        }
        $userAggregate = $this->userDao[$userId];
        if ($password !== $userAggregate->password()) {
            throw new PasswordIsNotMatchException();
        }
        return new class($userAggregate)
            implements AuthenticatableInterface
            {
                private User $userAggregate;
                public function __construct(User $userAggregate) { $this->userAggregate = $userAggregate; }
                public function getId(): int { return $this->userAggregate->id(); }
            };

    }
}
