<?php
declare(strict_types=1);

namespace App\infrastructure\AggregateRepository\User;

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

    /** @var self */
    private static $singleton;

    private function __construct() {}

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        if (!isset(self::$singleton)) {
            self::$singleton = new ForTestUserAggregateRepository();
        }

        return self::$singleton;
    }

    /**
     * @inheritDoc
     */
    public function getUserIdByEmail(string $email): int
    {
        // TODO: Implement getUserIdByEmail() method.
    }

    /**
     * @inheritDoc
     */
    public function isApplying(int $userId): bool
    {
        // TODO: Implement isApplying() method.
    }

    /**
     * @inheritDoc
     */
    public function save(User $user): int
    {
        return 0;
    }

    /**
     * @noinspection NonAsciiCharacters
     * @return string
     */
    public function 使われていないメールアドレスを生成する(): string
    {
        return "";
    }

    /**
     * @noinspection NonAsciiCharacters
     * @return string
     */
    private function ランダムな20桁の文字列を生成する(): string
    {
        return "";
    }
}