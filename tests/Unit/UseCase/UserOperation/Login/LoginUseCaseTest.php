<?php
declare(strict_types=1);

namespace Tests\Unit\UseCase\UserOperation\Login;

use App\Infrastructure\AggregateRepository\User\ForTestUserAggregateRepository;
use Bizlogics\Aggregate\UserAggregateRepositoryInterface;
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
        // 1. result->isSuccess() が true であること
        // 2. result->authObj() がオブジェクトを返すこと
    }

    /**
     * @noinspection NonAsciiCharacters
     * @test
     */
    public function システムへログインする_代替系列_入力されたメールアドレスの一致する既存の一般ユーザアカウントが無い場合(): void
    {
        // 1. result->isSuccess() が false であること
        // 2. result->exception() が NotExistException を返すこと
    }

    /**
     * @noinspection NonAsciiCharacters
     * @test
     */
    public function システムへログインする_代替系列_入力されたパスワードが、対象一般ユーザアカウントのパスワードと一致しない場合(): void
    {
        // 1. result->isSuccess() が false であること
        // 2. result->exception() が PasswordIsNotMatchException を返すこと
    }

}
