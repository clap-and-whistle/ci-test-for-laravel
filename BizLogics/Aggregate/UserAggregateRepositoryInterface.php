<?php
declare(strict_types=1);

namespace Bizlogics\Aggregate;

use Bizlogics\Aggregate\User\User;
use Bizlogics\UseCase\UserOperation\Login\AuthenticatableInterface;

interface UserAggregateRepositoryInterface
{
    public function getUserIdByEmail(string $email): int;

    public function save(User $userAggregate): int;

    public function isApplying(int $userId): bool;

    public function findById(int $userId): ?User;

    public function checkAuth(string $email, string $password): AuthenticatableInterface;
}
