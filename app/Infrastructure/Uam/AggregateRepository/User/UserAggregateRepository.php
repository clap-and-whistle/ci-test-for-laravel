<?php
declare(strict_types=1);

namespace App\Infrastructure\Uam\AggregateRepository\User;

use App\Infrastructure\Uam\AggregateRepository\User\Exception\NotExistException;
use App\Infrastructure\Uam\AggregateRepository\User\Exception\PasswordIsNotMatchException;
use App\Infrastructure\Uam\TableModel\UserAccountBase;
use Bizlogics\Uam\Aggregate\User\User;
use Bizlogics\Uam\Aggregate\UserAggregateRepositoryInterface;
use Bizlogics\Uam\UseCase\UserOperation\Login\AuthenticatableInterface;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class UserAggregateRepository implements UserAggregateRepositoryInterface
{
    public function getUserIdByEmail(string $email): int
    {
        $elqModel = UserAccountBase::where('email', $email)->first();
        return is_null($elqModel) ? 0 : $elqModel->id;
    }

    public function save(User $userAggregate): int
    {
        $userDao = new UserAccountBase();
        $userDao->email = $userAggregate->email();
        $password = $userAggregate->password();
        if ($password !== '') {
            // TODO: password カラムを更新するケースの判定メソッドをビジネスロジック側へ持たせるかどうか、検討が必要な気がする
            $userDao->password = Hash::make($password);
        }
        $userDao->account_status = $userAggregate->accountStatus();
        $userDao->save();

        return $userDao->id;
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
        $elqModel = UserAccountBase::find($userId);
        if (is_null($elqModel)) {
            return null;
        }

        if (! ($elqModel->id ?? 0) ) {
            throw new RuntimeException('データに不正があります: id');
        }

        return User::buildForFind(
            $elqModel->id,
            $elqModel->email,
            $elqModel->account_status
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
