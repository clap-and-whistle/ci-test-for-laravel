<?php
declare(strict_types=1);

namespace Tests\Unit\Uam\UseCase\UserOperation\CreateAccount;

use App\Infrastructure\Uam\AggregateRepository\User\ForTestUserAggregateRepository;
use Bizlogics\Uam\Aggregate\User\Exception\BirthDateStrInvalidException;
use Bizlogics\Uam\Aggregate\User\Exception\FullNameSizeTooLongException;
use Bizlogics\Uam\Aggregate\User\Exception\PasswordSizeTooShortException;
use Bizlogics\Uam\Aggregate\User\Exception\PasswordTypeCompositionInvalidException;
use Bizlogics\Uam\Aggregate\UserAggregateRepositoryInterface;
use Bizlogics\Uam\UseCase\UserOperation\CreateAccount\CreateAccountUseCase;
use Bizlogics\Uam\UseCase\UserOperation\CreateAccount\Exception\ApplyingException;
use Bizlogics\Uam\UseCase\UserOperation\CreateAccount\Exception\EmailAlreadyUsedException;
use Bizlogics\Uam\UseCase\UserOperation\CreateAccount\Result;
use Tests\BaseTestCase;

final class CreateAccountUseCaseTest extends BaseTestCase
{
    private UserAggregateRepositoryInterface|ForTestUserAggregateRepository $userRepo;

    protected function setUp(): void
    {
        $this->userRepo = ForTestUserAggregateRepository::getInstance();
    }

    private function makeUniqueEmail(): string
    {
        $email = $this->userRepo->使われていないメールアドレスを生成する();
        if ($email === "")  self::markTestIncomplete("このテストは、検証実施前に失敗しています");
        return $email;
    }

    /**
     * @noinspection NonAsciiCharacters
     * @test
     */
    public function アカウント作成の申請をする_基本系列()
    {
        $useCase = new CreateAccountUseCase($this->userRepo);
        $password = ForTestUserAggregateRepository::テスト用Password;

        $result = $useCase->execute($this->makeUniqueEmail(), $password);
        if (!$result->isSuccess())  var_dump([__METHOD__ => ['eMessage' => $result->eMessage()]]);
        self::assertSame(Result::class, get_class($result));
        self::assertTrue($result->isSuccess());
    }

    public function provideParamsForBasicStoryTest(): array
    {
        return [
            "nullの場合" => [null, null],
            "空文字の場合" => ["", ""],
            "適正値の場合" => ["ほげ田ほげ夫", "19770401"],
        ];
    }
    /**
     * @noinspection NonAsciiCharacters
     * @dataProvider provideParamsForBasicStoryTest
     * @test
     */
    public function アカウント作成の申請をする_基本系列_プロフィール入力欄追加分(?string $nameStr, ?string $birthDateStr)
    {
        $useCase = new CreateAccountUseCase($this->userRepo);
        $password = ForTestUserAggregateRepository::テスト用Password;

        $result = $useCase->execute($this->makeUniqueEmail(), $password, $nameStr, $birthDateStr);
        if (!$result->isSuccess())  var_dump([__METHOD__ => ['eMessage' => $result->eMessage()]]);
        self::assertSame(Result::class, get_class($result));
        self::assertTrue($result->isSuccess());
    }

    public function provideParamsForExistingUserTest(): array
    {
        return [
            "申請中" => [ForTestUserAggregateRepository::例外用_ユーザID_1_申請中メールアドレス, ApplyingException::class],
            "既に使用されている" => [ForTestUserAggregateRepository::例外用_ユーザID_2_既に使用されているメールアドレス, EmailAlreadyUsedException::class],
        ];
    }
    /**
     * @noinspection NonAsciiCharacters
     * @dataProvider provideParamsForExistingUserTest
     * @test
     */
    public function アカウント作成の申請をする_代替系列_入力されたメールアドレスが既に別のユーザに使用されている場合(string $email, string $expected): void
    {
        $useCase = new CreateAccountUseCase($this->userRepo);
        $password = ForTestUserAggregateRepository::テスト用Password;

        $result = $useCase->execute($email, $password);
        self::assertFalse($result->isSuccess());

        $exception = $result->exception();
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
    public function アカウント作成の申請をする_入力されたパスワードが要件を満たしていない場合(string $password, string $expected): void
    {
        $useCase = new CreateAccountUseCase($this->userRepo);

        $result = $useCase->execute($this->makeUniqueEmail(), $password);
        self::assertFalse($result->isSuccess());

        $exception = $result->exception();
        self::assertSame($expected, $exception ? get_class($exception) : "");
    }

    public function provideParamsForNameInputInvalidTest(): array
    {
        return [
            "氏名の文字列が長すぎる（マルチバイト）" => [
                "ほげ田ほげ夫ほげ田ほげ夫ほげ田ほげ夫ほげ田ほげ夫ほげ田ほげ夫ほげ田ほげ夫ほげ田ほげ夫ほげ田ほげ夫ほげ田ほげ夫ほげ田ほげ夫ほげ田ほげ夫ほげ田ほげ夫ほげ田ほげ夫ほげ田ほげ夫ほげ田ほげ夫ほげ田ほげ夫ほげ田ほげ夫ほげ田ほげ夫ほげ田ほげ夫ほげ田ほげ夫ほげ田ほげ夫ほげ田ほげ夫",
                FullNameSizeTooLongException::class
            ],
            "氏名の文字列が長すぎる（ASCII文字）" => [
                "ahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoahoaho",
                FullNameSizeTooLongException::class
            ],
        ];
    }
    /**
     * @noinspection NonAsciiCharacters
     * @dataProvider provideParamsForNameInputInvalidTest
     * @test
     */
    public function アカウント作成の申請をする_代替系列_氏名の文字列が長すぎる(string $nameStr, string $expected): void
    {
        $useCase = new CreateAccountUseCase($this->userRepo);
        $password = $this->userRepo::テスト用Password;

        $result = $useCase->execute(
            $this->makeUniqueEmail(),
            $password,
            $nameStr,
            null
        );
        self::assertFalse($result->isSuccess());
        $exception = $result->exception();
        self::assertSame($expected, $exception ? get_class($exception) : "");
    }

    public function provideParamsForBirthDateStrInputInvalidTest(): array
    {
        return [
            "生年月日の値が不正_01" => [
                "99999999",
                BirthDateStrInvalidException::class
            ],
            "生年月日の値が不正_02" => [
                "ほげほげ",
                BirthDateStrInvalidException::class
            ],
            "生年月日の値が不正_03" => [
                "19800340",
                BirthDateStrInvalidException::class
            ],
        ];
    }
    /**
     * @noinspection NonAsciiCharacters
     * @dataProvider provideParamsForBirthDateStrInputInvalidTest
     * @test
     */
    public function アカウント作成の申請をする_代替系列_生年月日の値が不正(string $birthDateStr, string $expected): void
    {
        $useCase = new CreateAccountUseCase($this->userRepo);
        $password = $this->userRepo::テスト用Password;

        $result = $useCase->execute(
            $this->makeUniqueEmail(),
            $password,
            null,
            $birthDateStr
        );
        self::assertFalse($result->isSuccess());
        $exception = $result->exception();
        self::assertSame($expected, $exception ? get_class($exception) : "");
    }
}
