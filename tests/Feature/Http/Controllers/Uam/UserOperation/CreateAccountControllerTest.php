<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Uam\UserOperation;

use App\Infrastructure\Uam\AggregateRepository\User\ForTestUserAggregateRepository;
use Bizlogics\Uam\Aggregate\UserAggregateRepositoryInterface;
use Tests\LaraTestCase;

final class CreateAccountControllerTest extends LaraTestCase
{
    private UserAggregateRepositoryInterface|ForTestUserAggregateRepository $userRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepo = ForTestUserAggregateRepository::getInstance();
    }

    /**
     * @noinspection NonAsciiCharacters
     * @test
     */
    public function アカウント作成の申請をする_「アカウント作成」画面(): void
    {
        $response = $this->get('/uam/create-account/new');
        $response->assertStatus(200);
    }

    /**
     * @noinspection NonAsciiCharacters
     * @test
     */
    public function アカウント作成の申請をする_一般ユーザを「申請中」として記録する(): void
    {
        $data = [
            'email' => $this->userRepo->使われていないメールアドレスを生成する(),
            'password' => ForTestUserAggregateRepository::テスト用Password,
            'full-name' => 'ほげTEST',
            'birth-date' => '19800101'
        ];

        $this->instance(
            /*
             * DIするインスタンスを「DAOを持たないテスト用Repository実装」に差し替える。
             * （Featureテストのような use CreatesApplication しているテストクラスの場合はこれをしてあげないとDBが飛ぶ恐れあり）
             */
            UserAggregateRepositoryInterface::class,
            ForTestUserAggregateRepository::getInstance()
        );

        $response = $this->post('/uam/create-account', $data);
        $response->assertRedirect('/');

        $data = [
            'email' => $this->userRepo->使われていないメールアドレスを生成する(),
            'password' => ForTestUserAggregateRepository::テスト用Password,
            'full-name' => 'ほげTEST',
            'birth-date' => '19800140'
        ];
        $response = $this->post('/uam/create-account', $data);
        $response->assertSessionHasErrors();

    }


}
