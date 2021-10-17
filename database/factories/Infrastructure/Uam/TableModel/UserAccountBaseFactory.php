<?php

namespace Database\Factories\Infrastructure\Uam\TableModel;

use App\Infrastructure\Uam\TableModel\UserAccountBase;
use Bizlogics\Uam\Aggregate\User\AccountStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAccountBaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserAccountBase::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->randomNumber(),
            'account_status' => $this->faker
                ->numberBetween(
                    AccountStatus::applying()->raw(),   // ライフサイクルの開始値
                    AccountStatus::deleted()->raw()     // ライフサイクルの終了値
                ),
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('hoge01TEST'),
        ];
    }
}
