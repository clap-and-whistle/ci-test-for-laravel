<?php
declare(strict_types=1);

namespace Tests\Unit\BizLogics\UseCase\UserOperation\CreateAccount;

use App\infrastructure\AggregateRepository\User\ForTestUserAggregateRepository;
use Bizlogics\Aggregate\User\Exception\PasswordSizeTooShortException;
use Bizlogics\Aggregate\User\Exception\PasswordTypeCompositionInvalidException;
use Bizlogics\Aggregate\User\UserAggregateRepositoryInterface;
use Bizlogics\UseCase\UserOperation\CreateAccount\CreateAccountUseCase;
use Bizlogics\UseCase\UserOperation\CreateAccount\Exception\ApplyingException;
use Bizlogics\UseCase\UserOperation\CreateAccount\Exception\EmailAlreadyUsedException;
use Tests\BaseTestCase;

final class CreateAccountUseCaseTest extends BaseTestCase
{
    /** @var UserAggregateRepositoryInterface|ForTestUserAggregateRepository */
    private $userRepo;

    protected function setUp(): void
    {
        $this->userRepo = ForTestUserAggregateRepository::getInstance();
    }

    /**
     * @noinspection NonAsciiCharacters
     * @test
     */
    public function 基本系列(): void
    {
        $useCase = new CreateAccountUseCase($this->userRepo);
        $email = $this->userRepo->使われていないメールアドレスを生成する();
        if ($email === "") {
            self::markTestIncomplete("このテストは、検証実施前に失敗しています");
        }
        $password = ForTestUserAggregateRepository::テスト用Password;

        $result = $useCase->execute($email, $password);
        if (!$result->isSuccess()) var_dump([__METHOD__=>['eMessage'=>$result->eMessage()]]);
        var_dump([__METHOD__=>[
            '$email'=>$email,
            '$password'=>$password,
            'masterAccountList'=>$this->userRepo->getMasterAccountList(),
        ]]);

        self::assertTrue($result->isSuccess());

    }

    public function provideParamsForExistingUserTest()
    {
        return [
            "申請中" => ["appraying@example.local", ApplyingException::class],
            "既に使用されている" => ["used@example.local", EmailAlreadyUsedException::class],
        ];
    }

    /**
     * @noinspection NonAsciiCharacters
     * @dataProvider provideParamsForExistingUserTest
     * @test
     */
    public function 代替系列_入力されたメールアドレスが既に別のユーザに使用されている場合(string $email, string $expected): void
    {
        $useCase = new CreateAccountUseCase($this->userRepo);
        $password = ForTestUserAggregateRepository::テスト用Password;

        $exception = $useCase->execute($email, $password)->exception();

        self::assertSame($expected, $exception ? get_class($exception) : "");
    }

    public function provideParamsForPasswordTest(): array
    {
        return [
            "パスワード長が短い" => ["Hoge1", PasswordSizeTooShortException::class],
            "パスワードが空" => ["", PasswordSizeTooShortException::class],
            "パスワードの文字構成が半角英小文字のみ"   => ["hogehoge", PasswordTypeCompositionInvalidException::class],
            "パスワードの文字構成が半角英大文字のみ"   => ["HOGEHOGE", PasswordTypeCompositionInvalidException::class],
            "パスワードの文字構成が半角数字のみ"     => ["12345678", PasswordTypeCompositionInvalidException::class],
            "パスワードの文字構成に半角数字が含まれてない" => ["FUGAfuga", PasswordTypeCompositionInvalidException::class],
            "パスワードの文字構成に半角小文字が含まれてない" => ["FUGA1234", PasswordTypeCompositionInvalidException::class],
            "パスワードの文字構成に半角大文字が含まれてない" => ["1234fuga", PasswordTypeCompositionInvalidException::class],
            "パスワードの文字構成に半角数値・半角大文字・半角小文字のいずれでもない文字が含まれている1"
                => ["1234ほげFuga", PasswordTypeCompositionInvalidException::class],
            "パスワードの文字構成に半角数値・半角大文字・半角小文字のいずれでもない文字が含まれている2"
                => ["1234_Fuga.", PasswordTypeCompositionInvalidException::class],
        ];
    }

    /**
     * @noinspection NonAsciiCharacters
     * @dataProvider provideParamsForPasswordTest
     * @test
     */
    public function 代替系列_入力されたパスワードが要件を満たしていない場合(string $password, string $expected): void
    {
        $useCase = new CreateAccountUseCase($this->userRepo);
        $email = $this->userRepo->使われていないメールアドレスを生成する();

        $exception = $useCase->execute($email, $password)->exception();

        self::assertSame($expected, $exception ? get_class($exception) : "");
    }

}
