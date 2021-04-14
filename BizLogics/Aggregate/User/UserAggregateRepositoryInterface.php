<?php
declare(strict_types=1);

namespace Bizlogics\Aggregate\User;

interface UserAggregateRepositoryInterface
{
    /**
     * @param string $email
     * @return int
     */
    public function getUserIdByEmail(string $email): int;

    /**
     * @param int $userId
     * @return bool
     */
    public function isApplying(int $userId): bool;

    /**
     * @param User $user
     * @return int
     */
    public function save(User $user): int;
}