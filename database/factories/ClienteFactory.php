<?php

namespace Database\Factories;

use App\Enums\TipoDocumentoEnum;
use App\Models\Cliente;
use App\Models\Distrito;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    /**
     * Define the model's default state.
     * fake()->unique()
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Hay 448 Distritos en la base de datos, elegimos 1 aleatoriamente
        $ID = rand(1, 448);
        // Pass ID value to distrito ID and query.
        $distritoId = $ID;
        // Retrieve existing IDs from related tables
        $distrito = Distrito::find($distritoId);
        // Get related cantone_id to $distrito and check to make sure it is not null
        $cantonesId = $distrito ? $distrito->cantones_id : null;
        // Get related provincias_id to $distrito and check to make sure it is not null
        $provinciasId = $distrito ? $distrito->provincias_id : null;



        // Actual Factory with column names and values to be assigned.
        return [
            // Random tipo_documento from TipoDocumentoEnum
            'tipo_documento' => $this->faker->randomElement(TipoDocumentoEnum::class),
            // Random DNI
            // dni() --> spanish faker class Id
            'documento' => $this->faker->unique()->dni(),
            // Random names
            'primer_nombre' => $this->faker->firstName(),
            'segundo_nombre' => $this->faker->firstName(),
            // Random Last Names
            'primer_apellido' => $this->faker->lastName(),
            'segundo_apellido' => $this->faker->lastName(),
            // Random email
            'email' => $this->faker->unique()->safeEmail(),
            // Random addresses.
            'direccion' => $this->faker->address(),
            // Random Document Tipe basen on TipoDocumentoEnum
            'tipo_documento' => $this->faker->randomElement(TipoDocumentoEnum::class),
            // Provincia, Canton, Distrito ---> from random distritos.
            'provincias_id' => $provinciasId,
            'cantones_id' => $cantonesId,
            'distritos_id' => $distrito,
            // Assign random user_id from Users seeded table to generate.
            // If user factory is run first, a larger set of users will exist
            // and the random user_id will be more realistic.
            // where user roll is not Administrador or Backoffice
            'user_id' => function() {
                    $rolePercentages = [
                        'Vendedor' => 88, // 88%
                        'Supervisor' => 10, // 10%
                        'Gerente' => 2, // 2%
                    ];
                
                    $totalUsers = User::count();
                    $roleCounts = [];
                
                    foreach ($rolePercentages as $role => $percentage) {
                        $roleCounts[$role] = (int) ($totalUsers * ($percentage / 100));
                    }
                
                    $role = array_rand($roleCounts);
                    $users = User::where('role', $role)->take($roleCounts[$role])->get();
                
                    return $users->random()->id;
                },
            // 'user_id' => User::whereNotIn('role', ['Administrador', 'Backoffice'])->get()->random()->id,
            // 'user_id' => User::all()->random()->id,
            // Random boolean documento_completo
            'documento_completo' => $this->faker->boolean('true', 'false'),
            // Random date
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            // 'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];

    }
}
