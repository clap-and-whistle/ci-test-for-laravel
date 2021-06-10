<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Open;

use Tests\LaraTestCase;

final class HomeControllerTest extends LaraTestCase
{
    /**
     * @noinspection NonAsciiCharacters
     * @test
     */
    public function サイトトップを表示する_「Home」画面(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }


}
