<?php
declare(strict_types=1);

namespace App\Infrastructure\Uam\AggregateRepository\AdminUser;

use App\Infrastructure\Uam\TableModel\AdminAccountBase;
use Bizlogics\Uam\Aggregate\AdminUser\AdminUser;
use Bizlogics\Uam\Aggregate\AdminUserAggregateRepositoryInterface;
use Bizlogics\Uam\Aggregate\Exception\NotExistException;
use Bizlogics\Uam\Aggregate\Exception\PasswordIsNotMatchException;
use Bizlogics\Uam\UseCase\AuthenticatableInterface;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminUserAggregateRepository implements AdminUserAggregateRepositoryInterface
{
    public function findById(int $id): ?AdminUser
    {
        $elqAdminAccountBase = AdminAccountBase::find($id);
        if (is_null($elqAdminAccountBase)) {
            return null;
        }

        if (! ($elqAdminAccountBase->id ?? 0) ) {
            throw new RuntimeException('データに不正があります: id');
        }

        return AdminUser::buildForFind(
            $elqAdminAccountBase->id,
            $elqAdminAccountBase->email,
            $elqAdminAccountBase->account_status
        );
    }

    /**
     * @inheritDoc
     */
    public function checkAuth(string $email, string $password): AuthenticatableInterface
    {
        /** @var AdminAccountBase $authenticatable */
        $authenticatable = AdminAccountBase::where('email', $email)->first();
        if (is_null($authenticatable)) {
            throw new NotExistException();
        }
        if (!Hash::check($password, $authenticatable->password)) {
            throw new PasswordIsNotMatchException();
        }
        return $authenticatable;
    }
}
