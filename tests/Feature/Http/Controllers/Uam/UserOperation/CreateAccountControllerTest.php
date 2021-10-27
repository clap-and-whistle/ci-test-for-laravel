<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Uam\UserOperation;

use Tests\Unit\Infrastructure\Uam\AggregateRepository\User\ForTestUserAggregateRepository;
use Bizlogics\Uam\Aggregate\UserAggregateRepositoryInterface;
use Tests\LaraTestCase;

final class CreateAccountControllerTest extends LaraTestCase
{
    private const INPUT_URL = '/uam/create-account/new';
    private const POST_URL = '/uam/create-account';

    private UserAggregateRepositoryInterface|ForTestUserAggregateRepository $userRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepo = ForTestUserAggregateRepository::getInstance();

        /*
         * DIするインスタンスを「DAOを持たないテスト用Repository実装」に差し替える。
         * （Featureテストのような use CreatesApplication しているテストクラスの場合は
         *  これをしてあげるか本番と別のDB(sqlite等)を使うように設定するかしないとDBが飛ぶ恐れあり）
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
    public function アカウント作成の申請をする_「アカウント作成」画面(): void
    {
        $response = $this->get(self::INPUT_URL);
        $response->assertStatus(200);
    }

    /**
     * @noinspection NonAsciiCharacters
     * @test
     */
    public function 基本コース1_POST結果をテスト_emailとpasswordのみ(): void
    {
        $data = [
            'email' => $this->userRepo->使われていないメールアドレスを生成する(),
            'password' => ForTestUserAggregateRepository::テスト用Password,
        ];

        $response = $this->post(self::POST_URL, $data);
        $response->assertRedirect('/');

        $data = [
            'email' => ForTestUserAggregateRepository::例外用_ユーザID_2_既に使用されているメールアドレス,
            'password' => ForTestUserAggregateRepository::テスト用Password,
        ];
        $response = $this
            ->from(self::INPUT_URL)
            ->post(self::POST_URL, $data);
        $response->assertRedirect(self::INPUT_URL);

    }

    /**
     * @noinspection NonAsciiCharacters
     * @test
     */
    public function 基本コース2_POST結果をテスト_フルオプション(): void
    {
        $data = [
            'email' => $this->userRepo->使われていないメールアドレスを生成する(),
            'password' => ForTestUserAggregateRepository::テスト用Password,
            'full-name' => 'ほげTEST',
            'birth-date' => '19800101'
        ];

        $response = $this->post(self::POST_URL, $data);
        $response->assertRedirect('/');

        $data = [
            'email' => $this->userRepo->使われていないメールアドレスを生成する(),
            'password' => ForTestUserAggregateRepository::テスト用Password,
            'full-name' => 'ほげTEST',
            'birth-date' => '19800140'  // 不正な入力値
        ];
        $response = $this
            ->from(self::INPUT_URL)
            ->post(self::POST_URL, $data);
        $response->assertRedirect(self::INPUT_URL);

    }

}
