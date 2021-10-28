<?php
declare(strict_types=1);

namespace Bizlogics\Uam\Aggregate\AdminUser;

use RuntimeException;

final class AccountStatus
{
    private const DISABLED  = 0;
    private const ENABLED   = 1;

    private int $rawValue;

    /**
     * AccountStatus constructor.
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
            case self::DISABLED:
                return new self(self::DISABLED);
            case self::ENABLED:
                return new self(self::ENABLED);
            default:
                throw new RuntimeException("不明な引数です");
        }
    }
    public static function disabled(): self
    {
        return new self(self::DISABLED);
    }
    public static function enabled(): self
    {
        return new self(self::ENABLED);
    }
}
