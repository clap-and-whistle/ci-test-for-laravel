<?php
declare(strict_types=1);

namespace Bizlogics\Uam\UseCase;

/**
 * Interface AuthenticatableInterface
 * 認証処理を扱うフレームワークの層から、依存性逆転の原則によりビジネスロジックを分離するために用意したインターフェース
 *
 * @package Bizlogics\UseCase\UserOperation\Login
 */
interface AuthenticatableInterface
{
    public function getId(): int;
}
