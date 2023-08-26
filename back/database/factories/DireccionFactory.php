<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Direccion>
 */
class DireccionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'direccion'=>$this->faker->text(50),
            'codigoPostal'=>$this->faker->numberBetween(1000,9999),
            'localidad'=>$this->faker->name()
        ];
    }
}
