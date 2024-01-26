<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    // generate factory for User model with the fallowing fields:
    // name, cedula, usuario, tlf, email, email_verified_at, fecha_ingreso, estado, role, password, remember_token


    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'cedula' => fake()->unique()->numerify('########'),
            'usuario' => fake()->unique()->userName(),
            'tlf' => fake()->unique()->numerify('########'),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'fecha_ingreso' => fake()->date(),
            'estado' => fake()->randomElement(['Activo', 'Inactivo']),
            'role' => fake()->randomElement(['Administrador', 'Vendedor', 'Supervisor', 'Gerente', 'Backoffice']),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
