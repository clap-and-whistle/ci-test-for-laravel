<?php
declare(strict_types=1);

namespace Bizlogics\Uam\Aggregate\AdminUser;

final class AdminUser
{
    private int $id;
    private string $email;
    private string $password;
    private AccountStatus $accountStatus;

    private function __construct(int $id, string $email, string $password, int $accountStatus)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->accountStatus = AccountStatus::getByRaw($accountStatus);
    }

    /**
     * テスト用データの作成を必要としているクラスでなら使って良い
     * @deprecated
     */
    public static function buildForTestData(int $id, string $email, string $password, int $accountStatus): self
    {
        return new self($id, $email, $password, $accountStatus);
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
}
