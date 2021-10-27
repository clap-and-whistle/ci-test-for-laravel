<?php

namespace Database\Factories\Infrastructure\Uam\TableModel;

use App\Infrastructure\Uam\TableModel\AdminAccountBase;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdminAccountBaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdminAccountBase::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->randomNumber(),
            'account_status' => $this->faker->numberBetween(0, 3),
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('hoge01TEST'),
        ];
    }
}
