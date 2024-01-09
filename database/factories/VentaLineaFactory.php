<?php

namespace Database\Factories;

use App\Enums\EstatusVentaLineaEnum;
use App\Enums\PlanesLibertyLineasEnum;
use App\Enums\PreciosPlanesLibertyLineasBenSampoEnum;
use App\Enums\PreciosPlanesLibertyLineasEnum;
use App\Enums\VentaLineasEnum;
use App\Models\Cliente;
use App\Models\Distrito;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VentaLinea>
 */
class VentaLineaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get key-value pairs from PlanesLibertyLineasEnum
        // Might want to refactor into BenSampoEnum
        $PlanesLibertyLineasValue =
            [
                'Plan1' => 'Plan @1',
                'Plan1Pluss' => 'Plan @1 Pluss',
                'Plan2' => 'Plan @2',
                'Plan3' => 'Plan @3',
                'Plan4' => 'Plan @4',
                'Plan5' => 'Plan @5',
                'Plan6' => 'Plan @6',
            ];
        // $PlanesLibertyLineasValue = PlanesLibertyLineasBenSampo::toSelectArray();

        // Get a random key from PlanesLibertyLineasEnum
        $randomEnumPlanesLibertyKey = $this->faker->randomElement(array_keys($PlanesLibertyLineasValue));

        // Get the corresponding name from PlanesLibertyLineasEnum using the random key
        $enumPlanesLibertyName = $PlanesLibertyLineasValue[$randomEnumPlanesLibertyKey];

        // Find the matching price in PreciosPlanesLibertyLineasEnum using the same key from PlanesLibertyLineasValue
        // I used the BenSampo Enum in order to generate this enum only for testing purposes.
        $enumPlanesLibertyPrice = PreciosPlanesLibertyLineasBenSampoEnum::fromkey($randomEnumPlanesLibertyKey)->value;

        // Random boolean value (true or false) for entrega_distinta
        $EntregaDistinta = $this->faker->boolean;

        // Random boolean value (true or false) for tlf_marcado
        $TlfVentaDistinto = $this->faker->boolean;

        //Tlf
        // Randomly select 6, 7, or 8
        $prefixTlf = $this->faker->randomElement([6, 7, 8]);
        // Random 7-digit number
        $randomSevenDigitsTlf = $this->faker->numberBetween(1000000, 9999999);
        // Concatenate prefix and random 7 digits
        $tlf = intval($prefixTlf.strval($randomSevenDigitsTlf));

        //tlf_marcado
        // Randomly select 6, 7, or 8
        $prefixTlfMarcado = $this->faker->randomElement([6, 7, 8]);
        // Random 7-digit number
        $randomSevenDigitsTlfMarcado = $this->faker->numberBetween(1000000, 9999999);
        // Concatenate prefix and random 7 digits
        $TlfMarcado = intval($prefixTlfMarcado.strval($randomSevenDigitsTlfMarcado));

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

        return [
            /**
             * Editar en algun punto para acceptar las modificaciones a vender multiples lineas, posibles precios
             *  $this->faker->randomElement(TipoDocumentoEnum::class),
             */
            'clientes_id' => Cliente::all()->random()->id,
            'VentaLinea' => $this->faker->randomElement(VentaLineasEnum::class), // VentaLineasEnum
            'plan' => $enumPlanesLibertyName, // PlanesLibertyLineasEnum
            'precio' => $enumPlanesLibertyPrice,
            'Estatus' => $this->faker->randomElement(EstatusVentaLineaEnum::class), // EstatusVentaLineaEnum
            'tlf' => $tlf,
            'tlf_venta_distinto' => $TlfVentaDistinto, // Bool val
            'tlf_marcado' => $TlfVentaDistinto ? $TlfMarcado : null, // telefono llamado cuando es distinto al de la venta
            'entrega_distinta' => $EntregaDistinta, // Bool val default 0 if 1 then direccion_entrega
            'direccion_entrega' => $EntregaDistinta ? $this->faker->address : '',
            'provincias_id' => $EntregaDistinta ? $provinciasId : null,
            'cantones_id' => $EntregaDistinta ? $cantonesId : null,
            'distritos_id' => $EntregaDistinta ? $distrito : null,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'user_id' => User::all()->random()->id,
        ];
    }
}
