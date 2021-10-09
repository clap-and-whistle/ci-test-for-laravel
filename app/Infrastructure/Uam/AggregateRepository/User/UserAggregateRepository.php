<?php
declare(strict_types=1);

namespace App\Infrastructure\Uam\AggregateRepository\User;

use App\Infrastructure\Uam\TableModel\UserAccountBase;
use App\Infrastructure\Uam\TableModel\UserAccountProfile;
use Bizlogics\Uam\Aggregate\Exception\NotExistException;
use Bizlogics\Uam\Aggregate\Exception\PasswordIsNotMatchException;
use Bizlogics\Uam\Aggregate\Exception\RegistrationProcessFailedException;
use Bizlogics\Uam\Aggregate\User\User;
use Bizlogics\Uam\Aggregate\UserAggregateRepositoryInterface;
use Bizlogics\Uam\UseCase\UserOperation\Login\AuthenticatableInterface;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Throwable;

class UserAggregateRepository implements UserAggregateRepositoryInterface
{
    public function getUserIdByEmail(string $email): int
    {
        $elqModel = UserAccountBase::where('email', $email)->first();
        return is_null($elqModel) ? 0 : $elqModel->id;
    }

    public function save(User $userAggregate): int
    {
        // TODO: まだ「アカウント作成」のケースだけのためのロジックなので、「user.id() が空かそうでないか」で分岐必要
        $userAccountBaseDao = new UserAccountBase();
        $userAccountProfileDao = $userAccountBaseDao->userAccountProfile ?? new UserAccountProfile();

        $userAccountBaseDao->email = $userAggregate->email();
        $password = $userAggregate->password();
        if ($password !== '') {
            // TODO: password カラムを更新するケースの判定メソッドをビジネスロジック側へ持たせるかどうか、検討が必要な気がする
            $userAccountBaseDao->password = Hash::make($password);
        }
        $userAccountBaseDao->account_status = $userAggregate->accountStatus();
        $userAccountProfileDao->full_name = $userAggregate->fullName();
        $userAccountProfileDao->birth_date_str = $userAggregate->birthDateStr();

        try {
            $userAccountBaseDao->save();
            $userId = $userAccountBaseDao->id;
            $userAccountProfileDao->user_account_base_id = $userId;
            $userAccountProfileDao->save();
        } catch (Throwable $e) {
            throw new RegistrationProcessFailedException($e->getMessage());
        }

        return $userId;
    }

    public function isApplying(int $userId): bool
    {
        $user = $this->findById($userId);
        if (is_null($user)) {
            throw new RuntimeException('該当するデータがHITしませんでした');
        }
        return $user->isApplying();
    }

    public function findById(int $userId): ?User
    {
        $elqUserAccountBase = UserAccountBase::find($userId);
        if (is_null($elqUserAccountBase)) {
            return null;
        }

        if (! ($elqUserAccountBase->id ?? 0) ) {
            throw new RuntimeException('データに不正があります: id');
        }

        $elqUserAccountProfile = $elqUserAccountBase->userAccountProfile;
        return User::buildForFind(
            $elqUserAccountBase->id,
            $elqUserAccountBase->email,
            $elqUserAccountBase->account_status,
            $elqUserAccountProfile->full_name,
            $elqUserAccountProfile->birth_date_str
        );
    }

    public function checkAuth(string $email, string $password): AuthenticatableInterface
    {
        /** @var UserAccountBase $authenticatable */
        $authenticatable = UserAccountBase::where('email', $email)->first();
        if (is_null($authenticatable)) {
            throw new NotExistException();
        }
        if (!Hash::check($password, $authenticatable->password)) {
            throw new PasswordIsNotMatchException();
        }
        return $authenticatable;
    }
}
