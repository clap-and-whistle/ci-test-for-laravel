<?php
declare(strict_types=1);

namespace Bizlogics\Uam\Aggregate;

use Bizlogics\Uam\Aggregate\User\User;
use Bizlogics\Uam\UseCase\UserOperation\Login\AuthenticatableInterface;

interface UserAggregateRepositoryInterface
{
    public function getUserIdByEmail(string $email): int;

    public function save(User $userAggregate): int;

    public function isApplying(int $userId): bool;

    public function findById(int $userId): ?User;

    public function checkAuth(string $email, string $password): AuthenticatableInterface;
}
