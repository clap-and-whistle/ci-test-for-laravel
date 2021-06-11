<?php
declare(strict_types=1);

namespace Bizlogics\Aggregate;

use Bizlogics\Aggregate\User\User;

interface UserAggregateRepositoryInterface
{
    public function getUserIdByEmail(string $email): int;

    public function save(User $userAggregate): int;

    public function isApplying(int $userId): bool;
}
