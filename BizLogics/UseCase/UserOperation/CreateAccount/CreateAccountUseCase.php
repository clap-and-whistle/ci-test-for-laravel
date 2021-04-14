<?php
declare(strict_types=1);

namespace Bizlogics\UseCase\UserOperation\CreateAccount;

use Bizlogics\Aggregate\User\Exception\PasswordSizeTooShortException;
use Bizlogics\Aggregate\User\Exception\PasswordTypeCompositionInvalidException;
use Bizlogics\Aggregate\User\User;
use Bizlogics\Aggregate\User\UserAggregateRepositoryInterface;
use Bizlogics\UseCase\UserOperation\CreateAccount\Exception\ApplyingException;
use Bizlogics\UseCase\UserOperation\CreateAccount\Exception\EmailAlreadyUsedException;

final class CreateAccountUseCase
{
    public const E_MSG_EMAIL_APPLYING = "申請中のメールアドレスです。";
    public const E_MSG_EMAIL_ALREADY_USED = "既に使用されているメールアドレスです。";
    public const E_MSG_PASSWORD_BASE = "パスワードが要件を満たしていません。";
    public const E_MSG_PASSWORD_SIZE_TOO_SHORT = "[" . User::PASSWORD_MIN_LENGTH . "文字以上必要]";
    public const E_MSG_PASSWORD_TYPE_INVALID = "[半角英数字で構成する]";

    private UserAggregateRepositoryInterface $userRepo;

    /**
     * CreateAccountUseCase constructor.
     */
    public function __construct(UserAggregateRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function execute(string $email, string $password): Result
    {
        $result = new Result();
        try {
            $userId = $this->userRepo->getUserIdByEmail($email);
            if ($userId > 0) {
                throw $this->userRepo->isApplying($userId)
                    ? new ApplyingException()
                    : new EmailAlreadyUsedException();
            }

            $user = User::buildForCreate($email, $password);
            $this->userRepo->save($user);
        } catch (ApplyingException $e) {
            $result->setFailure($e, self::E_MSG_EMAIL_APPLYING);
        } catch (EmailAlreadyUsedException $e) {
            $result->setFailure($e, self::E_MSG_EMAIL_ALREADY_USED);
        } catch (PasswordSizeTooShortException $e) {
            $result->setFailure($e, self::E_MSG_PASSWORD_BASE . self::E_MSG_PASSWORD_SIZE_TOO_SHORT);
        } catch (PasswordTypeCompositionInvalidException $e) {
            $result->setFailure($e, self::E_MSG_PASSWORD_BASE . self::E_MSG_PASSWORD_TYPE_INVALID);
        }
        return $result;
    }
}
