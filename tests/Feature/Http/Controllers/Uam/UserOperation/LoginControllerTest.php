<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Uam\UserOperation;

use Tests\LaraTestCase;

final class LoginControllerTest extends LaraTestCase
{
    /**
     * @noinspection NonAsciiCharacters
     * @test
     */
    public function システムへログインする_ログイン画面(): void
    {
        $response = $this->get('/uam/login/input');
        $response->assertStatus(200);
    }

}
