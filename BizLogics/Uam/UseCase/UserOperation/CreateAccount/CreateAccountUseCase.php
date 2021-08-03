<?php
declare(strict_types=1);

namespace Bizlogics\Uam\UseCase\UserOperation\CreateAccount;

use Bizlogics\Uam\Aggregate\User\Exception\BirthDateStrInvalidException;
use Bizlogics\Uam\Aggregate\User\Exception\FullNameSizeTooLongException;
use Bizlogics\Uam\Aggregate\User\Exception\PasswordSizeTooShortException;
use Bizlogics\Uam\Aggregate\User\Exception\PasswordTypeCompositionInvalidException;
use Bizlogics\Uam\Aggregate\User\User;
use Bizlogics\Uam\Aggregate\UserAggregateRepositoryInterface;
use Bizlogics\Uam\UseCase\UserOperation\CreateAccount\Exception\ApplyingException;
use Bizlogics\Uam\UseCase\UserOperation\CreateAccount\Exception\EmailAlreadyUsedException;

final class CreateAccountUseCase
{
    private UserAggregateRepositoryInterface $userRepos;
    public const E_MSG_EMAIL_APPLYING = "申請中のメールアドレスです。";
    public const E_MSG_EMAIL_ALREADY_USED = "既に使用されているメールアドレスです。";
    public const E_MSG_PASSWORD_BASE = "パスワードが要件を満たしていません。";
    public const E_MSG_PASSWORD_SIZE_TOO_SHORT = "[" . User::PASSWORD_MIN_LENGTH . "文字以上必要]";
    public const E_MSG_PASSWORD_TYPE_INVALID = "[半角大小英数字で構成する]";
    public const E_MSG_FULL_NAME_SIZE_TOO_LONG = "氏名の文字列が長すぎます。";
    public const E_MSG_BIRTH_DATE_STR_INVALID = "生年月日の文字列が不正です。";

    /**
     * CreateAccountUseCase constructor.
     */
    public function __construct(UserAggregateRepositoryInterface $userRepos)
    {
        $this->userRepos = $userRepos;
    }

    public function execute(string $email, string $password, ?string $fullName = null, ?string $birthDateStr = null): Result
    {
        $result = new Result();
        try {
            $userId = $this->userRepos->getUserIdByEmail($email);
            if ($userId > 0) {
                throw ($this->userRepos->isApplying($userId))
                    ? new ApplyingException()
                    : new EmailAlreadyUsedException();
            }
            $user = User::buildForCreate($email, $password, $fullName, $birthDateStr);
            $this->userRepos->save($user);
        } catch (ApplyingException $e) {
            $result->setFailure($e, self::E_MSG_EMAIL_APPLYING);
        } catch (EmailAlreadyUsedException $e) {
            $result->setFailure($e, self::E_MSG_EMAIL_ALREADY_USED);
        } catch (PasswordSizeTooShortException $e) {
            $result->setFailure($e, self::E_MSG_PASSWORD_BASE . self::E_MSG_PASSWORD_SIZE_TOO_SHORT);
        } catch (PasswordTypeCompositionInvalidException $e) {
            $result->setFailure($e, self::E_MSG_PASSWORD_BASE . self::E_MSG_PASSWORD_TYPE_INVALID);
        } catch (FullNameSizeTooLongException $e) {
            $result->setFailure($e, self::E_MSG_FULL_NAME_SIZE_TOO_LONG);
        } catch (BirthDateStrInvalidException $e) {
            $result->setFailure($e, self::E_MSG_BIRTH_DATE_STR_INVALID);
        }

        return $result;
    }
}
