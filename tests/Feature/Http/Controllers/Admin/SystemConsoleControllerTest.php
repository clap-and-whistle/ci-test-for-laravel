<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Admin;

use App\Http\Controllers\Admin\SystemConsoleController;
use App\Http\Controllers\Uam\SysAdminOperation\LoginController;
use App\Infrastructure\Uam\TableModel\AdminAccountBase;
use Bizlogics\Uam\Aggregate\AdminUser\AccountStatus;
use Bizlogics\Uam\Aggregate\AdminUser\AdminUser;
use Bizlogics\Uam\Aggregate\AdminUserAggregateRepositoryInterface;
use Tests\LaraTestCase;
use Tests\Unit\Infrastructure\Uam\AggregateRepository\AdminUser\ForTestAdminUserAggregateRepository;

final class SystemConsoleControllerTest extends LaraTestCase
{
    private AdminUserAggregateRepositoryInterface $adminRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminRepo = ForTestAdminUserAggregateRepository::getInstance();

        /*
         * DIするインスタンスを「DAOを持たないテスト用Repository実装」に差し替える。
         */
        $this->instance(
            AdminUserAggregateRepositoryInterface::class,
            $this->adminRepo
        );
    }

    /**
     * @noinspection NonAsciiCharacters
     * @test
     */
    public function indexAction_認証無しでログイン後の遷移先ページへアクセスする(): void
    {
        $response = $this->get(route(SystemConsoleController::URL_ROUTE_NAME));
        $response->assertStatus(302);
        $response->assertRedirect(route(LoginController::URL_ROUTE_NAME_INPUT_ACTION));
    }

    /**
     * @noinspection NonAsciiCharacters
     * @test
     */
    public function indexAction_認証OKでログイン後の遷移先ページへアクセスする(): void
    {
        $adminUser = AdminAccountBase::factory()->make(['account_status'=>AccountStatus::enabled()->raw()]);
        $this->adminRepo->save(AdminUser::buildForTestData(
            $adminUser->id,
            $adminUser->email,
            $adminUser->password,
            $adminUser->account_status
        ));

        $response = $this
            ->actingAs($adminUser, 'admin')
            ->get(route(SystemConsoleController::URL_ROUTE_NAME));
        $response->assertStatus(200);
        $response->assertSeeText($adminUser->email);
    }

}
