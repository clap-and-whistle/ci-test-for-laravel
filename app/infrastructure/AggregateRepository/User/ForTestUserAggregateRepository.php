<?php
declare(strict_types=1);

namespace App\infrastructure\AggregateRepository\User;

use Exception;
use Bizlogics\Aggregate\User\AccountStatus;
use Bizlogics\Aggregate\User\User;
use Bizlogics\Aggregate\User\UserAggregateRepositoryInterface;

class ForTestUserAggregateRepository implements UserAggregateRepositoryInterface
{
    public const テスト用Password = "hogeFuga123";
    public const テスト用の誤ったPassword = "hogePiyo123";

    public const 例外用_ユーザID_1_申請中 = 1;
    public const 例外用_ユーザID_1_申請中メールアドレス = "appraying@example.local";

    public const 例外用_ユーザID_2_既に使用中 = 2;
    public const 例外用_ユーザID_2_既に使用されているメールアドレス = "used@example.local";

    private static self $singleton;

    /** @var User[]  */
    private array $masterAccountList = [];

    private function __construct() {
        $this->save(new User(
                self::例外用_ユーザID_1_申請中
                , self::例外用_ユーザID_1_申請中メールアドレス
                , self::テスト用Password
                , AccountStatus::applying()->raw())
        );
        $this->save(new User(
                self::例外用_ユーザID_2_既に使用中
                , self::例外用_ユーザID_2_既に使用されているメールアドレス
                , self::テスト用Password
                , AccountStatus::inOperation()->raw())
        );
    }

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        if (!isset(self::$singleton)) {
            self::$singleton = new self();
        }

        return self::$singleton;
    }

    /**
     * @inheritDoc
     */
    public function getUserIdByEmail(string $email): int
    {
        foreach ($this->masterAccountList as $user) {
            if ($user->email() === $email) {
                return $user->id();
            }
        }
        return 0;
    }

    /**
     * @inheritDoc
     */
    public function isApplying(int $userId): bool
    {
        $user = $this->masterAccountList[$userId];
        return $user->isApplying();
    }

    /**
     * @inheritDoc
     */
    public function save(User $user): int
    {
        $id = $user->id();
        if ($id === 0) {
            $keys = array_keys($this->masterAccountList);
            sort($keys);
            $id = array_pop($keys) + 1;
            $user = new User(
                $id
                , $user->email()
                , $user->password()
                , $user->accountStatus()
            );
        }
        $this->masterAccountList[$id] = $user;
        return $id;
    }

    /**
     * @noinspection NonAsciiCharacters
     * @return string
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
     * @return string
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

    public function getMasterAccountList(): array
    {
        return $this->masterAccountList;
    }
}
