<?php
declare(strict_types=1);

namespace Bizlogics\Aggregate\User;

use RuntimeException;

final class AccountStatus
{
    private const APPLYING      = 0;
    private const IN_OPERATION  = 1;
    private const STOPPING      = 2;
    private const DELETED       = 3;

    private int $rawValue;

    /**
     * AccountStatus constructor.
     * @param int $rawValue
     */
    private function __construct(int $rawValue)
    {
        $this->rawValue = $rawValue;
    }

    public function raw(): int
    {
        return $this->rawValue;
    }
    public static function getByRaw(int $raw): self
    {
        switch ($raw) {
            case self::APPLYING:
                return new self(self::APPLYING);
            case self::IN_OPERATION:
                return new self(self::IN_OPERATION);
            case self::STOPPING:
                return new self(self::STOPPING);
            case self::DELETED:
                return new self(self::DELETED);
            default:
                throw new RuntimeException("不明な引数です");
        }
    }

    public static function applying(): self
    {
        return new self(self::APPLYING);
    }

    public static function inOperation()
    {
        return new self(self::IN_OPERATION);
    }

    public function isApplying()
    {
        return $this->rawValue === self::APPLYING;
    }
}
