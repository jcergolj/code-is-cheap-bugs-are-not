<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'total' => $this->faker->numberBetween(100, 10000),
            'payment_token' => 'tok_'.$this->faker->uuid(),
            'status' => 'pending',
        ];
    }
}
