<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        
        return [
            'title'=> $this->faker->realText(20, 1),
            'text'=> $this->faker->realText(200, 2),
            'user_id'=> rand(1, 10)
        ];
    }
}
