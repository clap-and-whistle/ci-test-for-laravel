<?php
declare(strict_types=1);

namespace App\infrastructure\AggregateRepository\User;


use Bizlogics\Aggregate\User\User;
use Bizlogics\Aggregate\User\UserAggregateRepositoryInterface;

class UserAggregateRepository implements UserAggregateRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function getUserIdByEmail(string $email): int
    {
        // TODO: Implement getUserIdByEmail() method.
    }

    /**
     * @inheritDoc
     */
    public function isApplying(int $userId): bool
    {
        // TODO: Implement isApplying() method.
    }

    /**
     * @inheritDoc
     */
    public function save(User $user): int
    {
        // TODO: Implement save() method.
    }
}