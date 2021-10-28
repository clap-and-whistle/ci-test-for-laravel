<?php
declare(strict_types=1);

namespace Tests\Unit\Bizlogics\Uam\UseCase\SysAdminOperation\Login;

use Bizlogics\Uam\Aggregate\AdminUserAggregateRepositoryInterface;
use Tests\Unit\Infrastructure\Uam\AggregateRepository\AdminUser\ForTestAdminUserAggregateRepository;
use Bizlogics\Uam\Aggregate\Exception\NotExistException;
use Bizlogics\Uam\Aggregate\Exception\PasswordIsNotMatchException;
use Bizlogics\Uam\UseCase\SysAdminOperation\Login\LoginUseCase;
use Bizlogics\Uam\UseCase\AuthenticatableInterface;
use Tests\BaseTestCase;

class LoginUseCaseTest extends BaseTestCase
{
    private AdminUserAggregateRepositoryInterface $adminRepos;

    protected function setUp(): void
    {
        $this->adminRepos = ForTestAdminUserAggregateRepository::getInstance();
    }

    /**
     * @noinspection NonAsciiCharacters
     * @test
     */
    public function システム管理コンソールへログインする_基本系列(): void
    {
        $useCase = new LoginUseCase($this->adminRepos);
        $result = $useCase->execute(
            ForTestAdminUserAggregateRepository::テスト用_管理者ID_1_Enabledメールアドレス,
            ForTestAdminUserAggregateRepository::テスト用Password
        );
        if (!$result->isSuccess()) {
            var_dump([__METHOD__ => ['eMessage' => $result->eMessage()]]);
        }

        // 1. result->isSuccess() が true であること
        self::assertTrue($result->isSuccess());

        // 2. result->authObj() の返すオブジェクトが期待通りであること
        self::assertInstanceOf(AuthenticatableInterface::class, $result->authObj());
    }

    /**
     * @noinspection NonAsciiCharacters
     * @test
     */
    public function システム管理コンソールへログインする_代替系列_入力されたメールアドレスの一致する既存の管理ユーザアカウントが無い(): void
    {
        $useCase = new LoginUseCase($this->adminRepos);
        $result = $useCase->execute(
            ForTestAdminUserAggregateRepository::システム管理コンソールログイン時例外用_使用されていないメールアドレス,
            ForTestAdminUserAggregateRepository::テスト用Password
        );
        $exception = $result->exception();

        // 1. $result->isSuccess() が false であること
        self::assertFalse($result->isSuccess());

        // 2. $result->eMessage() が 期待された通りの文字列であること
        self::assertSame(LoginUseCase::E_MSG_NOT_EXISTS, $result->eMessage());

        // 3. $result->exception() が nullでないこと
        self::assertNotNull($exception);

        // 4. $result->exception() が NotExistException を返すこと
        self::assertSame(NotExistException::class, $exception ? get_class($exception) : "");
    }

    /**
     * @noinspection NonAsciiCharacters
     * @test
     */
    public function システム管理コンソールへログインする_代替系列_入力されたパスワードが、対象管理ユーザアカウントのパスワードと一致しない(): void
    {
        $useCase = new LoginUseCase($this->adminRepos);
        $result = $useCase->execute(
            ForTestAdminUserAggregateRepository::テスト用_管理者ID_1_Enabledメールアドレス,
            ForTestAdminUserAggregateRepository::テスト用の誤ったPassword
        );
        $exception = $result->exception();

        // 1. result->isSuccess() が false であること
        self::assertFalse($result->isSuccess());

        // 2. $result->eMessage() が 期待された通りの文字列であること
        self::assertSame(LoginUseCase::E_MSG_PASSWORD_IS_NOT_MATCH, $result->eMessage());

        // 3. $result->exception() が nullでないこと
        self::assertNotNull($exception);

        // 4. result->exception() が PasswordIsNotMatchException を返すこと
        self::assertSame(PasswordIsNotMatchException::class, $exception ? get_class($exception) : "");
    }

}
