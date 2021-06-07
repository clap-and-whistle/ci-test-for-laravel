<?php
declare(strict_types=1);

namespace Bizlogics\UseCase\UserOperation\CreateAccount;

use RuntimeException;

final class Result
{
    /** @var bool */
    private $success = true;

    /** @var RuntimeException */
    private $exception;

    /** @var string */
    private $eMessage;

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
}
