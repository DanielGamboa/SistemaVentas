<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NumeroReferencia>
 */
class NumeroReferenciaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //Faker numero de referencia can only start with 6,7,8 and must be 8 digits long

            'numeroreferencia' => $this->faker->unique()->numberBetween(60000000, 89999999),
            'contacto' => $this->faker->name(),

        ];
    }
}
