<?php
declare(strict_types=1);

namespace Bizlogics\UseCase\UserOperation\Login;

use RuntimeException;

final class Result
{
    private bool $success = false;

    private AuthenticatableInterface $authObj;

    private ?RuntimeException $exception;

    private string $eMessage;

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function setAuthObj(AuthenticatableInterface $authObj): self
    {
        $this->success = true;
        $this->authObj = $authObj;
        return $this;
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

    public function authObj(): AuthenticatableInterface
    {
        return $this->authObj;
    }
}
