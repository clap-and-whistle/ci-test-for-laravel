<?php
declare(strict_types=1);

namespace Bizlogics\Uam\UseCase\UserOperation\CreateAccount;

use RuntimeException;

final class Result
{
    private bool $success = true;

    private ?RuntimeException $exception;

    private string $eMessage;

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function setFailure(RuntimeException $exception, string $eMessage): self
    {
        $this->success = false;
        $this->exception = $exception;
        $this->eMessage = $eMessage;
        return $this;
    }

    public function exception(): ?RuntimeException
    {
        return $this->exception ?? null;
    }

    public function eMessage(): string
    {
        return $this->eMessage ?? '';
    }
}
