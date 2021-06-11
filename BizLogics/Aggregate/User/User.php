<?php
declare(strict_types=1);

namespace Bizlogics\Aggregate\User;

final class User
{
    public const PASSWORD_MIN_LENGTH = 8;

    private int $id;
    private string $email;
    private string $password;
    private AccountStatus $accountStatus;

    /**
     * User constructor.
     */
    public function __construct(int $id, string $email, string $password, int $accountStatus)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->accountStatus = AccountStatus::getByRaw($accountStatus);
    }

    public static function buildForCreate(string $email, string $password): self
    {
        return new self(0, $email, $password, AccountStatus::applying()->raw());
    }

    public function id(): int
    {
        return $this->id;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function accountStatus(): int
    {
        return $this->accountStatus->raw();
    }

    public function isApplying(): bool
    {
        return $this->accountStatus->isApplying();
    }
}
