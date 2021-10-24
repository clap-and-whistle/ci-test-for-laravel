<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Desk;

use App\Http\Controllers\Desk\MyWorkController;
use App\Http\Controllers\Uam\UserOperation\LoginController;
use App\Infrastructure\Uam\TableModel\UserAccountBase;
use Bizlogics\Uam\Aggregate\User\AccountStatus;
use Bizlogics\Uam\Aggregate\User\User;
use Bizlogics\Uam\Aggregate\UserAggregateRepositoryInterface;
use Tests\LaraTestCase;
use Tests\Unit\Infrastructure\Uam\AggregateRepository\User\ForTestUserAggregateRepository;

class MyWorkControllerTest extends LaraTestCase
{
    private UserAggregateRepositoryInterface $userRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepo = ForTestUserAggregateRepository::getInstance();

        /*
         * DIするインスタンスを「DAOを持たないテスト用Repository実装」に差し替える。
         */
        $this->instance(
            UserAggregateRepositoryInterface::class,
            $this->userRepo
        );
    }

    /**
     * @noinspection NonAsciiCharacters
     * @test
     */
    public function indexAction_認証無しでログイン後の遷移先ページへアクセスする(): void
    {
        $response = $this->get(route(MyWorkController::URL_ROUTE_NAME));
//        $response->assertUnauthorized();  // なぜか 401 にならず 302.    // 詳細は未調査
        $response->assertRedirect(route(LoginController::URL_ROUTE_NAME_INPUT_ACTION));
    }

    /**
     * @noinspection NonAsciiCharacters
     * @test
     */
    public function indexAction_認証OKでログイン後の遷移先ページへアクセスする(): void
    {
        $user = UserAccountBase::factory()->make(['account_status'=>AccountStatus::inOperation()->raw()]);
        $this->userRepo->save(User::buildForTestData(
            $user->id,
            $user->email,
            $user->password,
            $user->account_status
        ));

        $response = $this
            ->actingAs($user, 'web')
            ->get(route(MyWorkController::URL_ROUTE_NAME));
        $response->assertStatus(200);
    }

}
