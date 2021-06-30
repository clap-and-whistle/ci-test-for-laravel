<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Uam\UserOperation;

use Tests\LaraTestCase;

final class CreateAccountControllerTest extends LaraTestCase
{
    /**
     * @noinspection NonAsciiCharacters
     * @test
     */
    public function アカウント作成の申請をする_「アカウント作成」画面(): void
    {
        $response = $this->get('/uam/create-account/new');
        $response->assertStatus(200);
    }


}
