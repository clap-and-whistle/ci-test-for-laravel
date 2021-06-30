<?php
declare(strict_types=1);

namespace Tests\Unit\Uam\UseCase\UserOperation\Login;

use App\Infrastructure\Uam\AggregateRepository\User\Exception\NotExistException;
use App\Infrastructure\Uam\AggregateRepository\User\Exception\PasswordIsNotMatchException;
use App\Infrastructure\Uam\AggregateRepository\User\ForTestUserAggregateRepository;
use Bizlogics\Uam\Aggregate\UserAggregateRepositoryInterface;
use Bizlogics\Uam\UseCase\UserOperation\Login\AuthenticatableInterface;
use Bizlogics\Uam\UseCase\UserOperation\Login\LoginUseCase;
use Tests\BaseTestCase;

class LoginUseCaseTest extends BaseTestCase
{
    private UserAggregateRepositoryInterface $userRepos;

    protected function setUp(): void
    {
        $this->userRepos = ForTestUserAggregateRepository::getInstance();
    }

    /**
     * @noinspection NonAsciiCharacters
     * @test
     */
    public function システムへログインする_基本系列(): void
    {
        $useCase = new LoginUseCase($this->userRepos);
        $result = $useCase->execute(
            ForTestUserAggregateRepository::例外用_ユーザID_1_申請中メールアドレス,
            ForTestUserAggregateRepository::テスト用Password
        );
        if (!$result->isSuccess()) {
            var_dump([__METHOD__ => ['eMessage' => $result->eMessage()]]);
        }

        // 1. result->isSuccess() が true であること
        self::assertTrue($result->isSuccess());

        // 2. result->authObj() がオブジェクトを返すこと
        self::assertInstanceOf(AuthenticatableInterface::class, $result->authObj());
    }

    /**
     * @noinspection NonAsciiCharacters
     * @test
     */
    public function システムへログインする_代替系列_入力されたメールアドレスの一致する既存の一般ユーザアカウントが無い場合(): void
    {
        $useCase = new LoginUseCase($this->userRepos);
        $result = $useCase->execute(
            ForTestUserAggregateRepository::システムログイン時例外用_使用されていないメールアドレス,
            ForTestUserAggregateRepository::テスト用Password
        );

        // 1. result->isSuccess() が false であること
        self::assertFalse($result->isSuccess());

        // 2. result->exception() が NotExistException を返すこと
        $exception = $result->exception();
        self::assertSame(NotExistException::class, $exception ? get_class($exception) : "");
    }

    /**
     * @noinspection NonAsciiCharacters
     * @test
     */
    public function システムへログインする_代替系列_入力されたパスワードが、対象一般ユーザアカウントのパスワードと一致しない場合(): void
    {
        $useCase = new LoginUseCase($this->userRepos);
        $result = $useCase->execute(
            ForTestUserAggregateRepository::例外用_ユーザID_2_既に使用されているメールアドレス,
            ForTestUserAggregateRepository::テスト用の誤ったPassword
        );

        // 1. result->isSuccess() が false であること
        self::assertFalse($result->isSuccess());

        // 2. result->exception() が PasswordIsNotMatchException を返すこと
        $exception = $result->exception();
        self::assertSame(PasswordIsNotMatchException::class, $exception ? get_class($exception) : "");
    }

}
