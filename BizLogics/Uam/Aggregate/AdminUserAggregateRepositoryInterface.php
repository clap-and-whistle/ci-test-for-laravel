<?php
declare(strict_types=1);

namespace Bizlogics\Uam\Aggregate;

use Bizlogics\Uam\Aggregate\AdminUser\AdminUser;
use Bizlogics\Uam\Aggregate\Exception\NotExistException;
use Bizlogics\Uam\Aggregate\Exception\PasswordIsNotMatchException;
use Bizlogics\Uam\UseCase\AuthenticatableInterface;

interface AdminUserAggregateRepositoryInterface
{
    public function findById(int $id): ?AdminUser;

    /**
     * @throws NotExistException
     * @throws PasswordIsNotMatchException
     */
    public function checkAuth(string $email, string $password): AuthenticatableInterface;

}
