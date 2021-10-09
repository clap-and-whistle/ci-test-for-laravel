<?php
declare(strict_types=1);

namespace Bizlogics\Uam\Aggregate\User;

use Bizlogics\Uam\Aggregate\User\Exception\BirthDateStrInvalidException;
use Bizlogics\Uam\Aggregate\User\Exception\FullNameSizeTooLongException;
use Bizlogics\Uam\Aggregate\Exception\PasswordSizeTooShortException;
use Bizlogics\Uam\Aggregate\Exception\PasswordTypeCompositionInvalidException;

final class User
{
    public const PASSWORD_MIN_LENGTH = 8;
    public const FULLNAME_MAX_LENGTH = 256;

    private int $id;
    private string $email;
    private string $password;
    private string $fullName;
    private string $birthDateStr;
    private AccountStatus $accountStatus;

    /**
     * User constructor.
     */
    private function __construct(int $id, string $email, string $password, int $accountStatus, ?string $fullName = null, ?string $birthDateStr = null)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->accountStatus = AccountStatus::getByRaw($accountStatus);
        $this->fullName = $fullName ?? "";
        $this->birthDateStr = $birthDateStr ?? "";
    }

    /**
     * テスト用データの作成を必要としているクラスでなら使って良い
     * @deprecated
     */
    public static function buildForTestData(int $id, string $email, string $password, int $accountStatus, ?string $fullName = null, ?string $birthDateStr = null): self
    {
        return new self($id, $email, $password, $accountStatus, $fullName, $birthDateStr);
    }

    public static function buildForFind(int $id, string $email, int $accountStatus, ?string $fullName = null, ?string $birthDateStr = null): self
    {
        return new self($id, $email, '', $accountStatus, $fullName, $birthDateStr);
    }

    /**
     * @throws PasswordSizeTooShortException
     * @throws PasswordTypeCompositionInvalidException
     * @throws FullNameSizeTooLongException
     * @throws BirthDateStrInvalidException
     */
    public static function buildForCreate(string $email, string $password, ?string $fullName = null, ?string $birthDateStr = null): self
    {
        $user = new self(0, $email, $password, AccountStatus::applying()->raw(), $fullName, $birthDateStr);
        if (!$user->isValidPasswordSize())  throw new PasswordSizeTooShortException();
        if (!$user->isValidPasswordCharacterTypeComposition())  throw new PasswordTypeCompositionInvalidException();
        if (!$user->isValidFullNameSize())  throw new FullNameSizeTooLongException();
        if (!$user->isValidBirthDateStr())  throw new BirthDateStrInvalidException();

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

    public function accountStatus(): int
    {
        return $this->accountStatus->raw();
    }

    public function fullName(): string
    {
        return $this->fullName;
    }

    public function birthDateStr(): string
    {
        return $this->birthDateStr;
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

    private function isValidFullNameSize(): bool
    {
        return mb_strwidth($this->fullName) < self::FULLNAME_MAX_LENGTH;
    }

    private function isValidBirthDateStr(): bool
    {
        $str = $this->birthDateStr;
        if (strlen($str) === 0)  return true;
        if (preg_match("/^.*[^0-9].*$/", $str))   return false;

        return checkdate(
            (int)substr($str, 4, 2),    // month
            (int)substr($str, 6, 2),    // day
            (int)substr($str, 0, 4)     // year
        );
    }
}
