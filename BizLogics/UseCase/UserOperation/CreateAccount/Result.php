<?php
declare(strict_types=1);

namespace Bizlogics\UseCase\UserOperation\CreateAccount;

use RuntimeException;

final class Result
{
    private bool $success;

    private ?RuntimeException $exception = null;

    private ?string $eMessage = null;

    /**
     * Result constructor.
     */
    public function __construct($success = true)
    {
        $this->success = $success;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param RuntimeException $exception
     * @param string $eMessage
     * @return Result
     */
    public function setFailure(RuntimeException $exception, string $eMessage): self
    {
        $this->success = false;
        $this->exception = $exception;
        $this->eMessage = $eMessage;
        return $this;
    }

    public function exception(): ?RuntimeException
    {
        return $this->exception;
    }

    public function eMessage(): ?string
    {
        return $this->eMessage;
    }
}
