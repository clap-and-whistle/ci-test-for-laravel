<?php
declare(strict_types=1);

namespace Bizlogics\Aggregate\User;


use Bizlogics\Aggregate\User\Exception\PasswordSizeTooShortException;
use Bizlogics\Aggregate\User\Exception\PasswordTypeCompositionInvalidException;

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

    /**
     * @param string $email
     * @param string $password
     * @return static
     */
    public static function buildForCreate(string $email, string $password): self
    {
        $user = new self(0, $email, $password, AccountStatus::applying()->raw());
        if (!$user->isValidPasswordSize())  throw new PasswordSizeTooShortException();
        if (!$user->isValidPasswordCharacterTypeComposition())  throw new PasswordTypeCompositionInvalidException();

        return $user;
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

    public function accountStatus()
    {
        return $this->accountStatus->raw();
    }

    public function isApplying(): bool
    {
        return $this->accountStatus->isApplying();
    }

    private function isValidPasswordSize(): bool
    {
        return strlen($this->password) >= self::PASSWORD_MIN_LENGTH;
    }

    private function isValidPasswordCharacterTypeComposition(): bool
    {
        preg_match_all("/[0-9]/", $this->password, $numMatches);
        preg_match_all("/[a-z]/",$this->password, $smallMatches);;
        preg_match_all("/[A-Z]/", $this->password, $largeMatches);;
        preg_match_all("/[^0-9a-zA-Z]/", $this->password, $illegalMatches);
        $numMatchesCount    = count($numMatches[0]);
        $smallMatchesCount  = count($smallMatches[0]);
        $largeMatchesCount  = count($largeMatches[0]);
        $illegalMatchesCount = count($illegalMatches[0]);
        $allMatchesCount = $numMatchesCount + $smallMatchesCount + $largeMatchesCount;

        return  $allMatchesCount === strlen($this->password)
                && $numMatchesCount   > 0
                && $smallMatchesCount > 0
                && $largeMatchesCount > 0
                && $illegalMatchesCount === 0;
    }

}
