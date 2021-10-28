<?php
declare(strict_types=1);

namespace App\Infrastructure\Uam\AggregateRepository\AdminUser;

use Bizlogics\Uam\Aggregate\AdminUser\AdminUser;
use Bizlogics\Uam\Aggregate\AdminUserAggregateRepositoryInterface;
use Bizlogics\Uam\Aggregate\Exception\NotExistException;
use Bizlogics\Uam\Aggregate\Exception\PasswordIsNotMatchException;
use Bizlogics\Uam\UseCase\AuthenticatableInterface;

class AdminUserAggregateRepository implements AdminUserAggregateRepositoryInterface
{

    public function findById(int $id): ?AdminUser
    {
        // TODO: Implement findById() method.
        return new AdminUser();
    }

    /**
     * @inheritDoc
     */
    public function checkAuth(string $email, string $password): AuthenticatableInterface
    {
        // TODO: Implement checkAuth() method.
        return new class implements AuthenticatableInterface {

            public function getId(): int
            {
                return 0;
            }
        };
    }
}
