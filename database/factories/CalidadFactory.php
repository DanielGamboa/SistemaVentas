<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Calidad;
use App\Models\User;
use App\Enums\Calidad\MotivoEvaluacionEnum;
use App\Models\VentaLinea;
// App\Enums\Calidad\ Enums
use App\Enums\Calidad\BienvenidaEnum;
use App\Enums\Calidad\EmpatiaEnum;
use App\Enums\Calidad\SondeoEnum;
use App\Enums\Calidad\EscuchaActivaEnum;
use App\Enums\Calidad\OfertaComercialEnum;
use App\Enums\Calidad\SolicitudNumeroAlternativoEnum;
use App\Enums\Calidad\AclaraDudasClienteEnum;
use App\Enums\Calidad\ManejoObjecionesEnum;
use App\Enums\Calidad\GenerarVentasIrregularesEnum;
use App\Enums\Calidad\AceptacionServicioEnum;
use App\Enums\Calidad\TecnicasCierreEnum;
use App\Enums\Calidad\UtilizaTecnicasCierreEnum;
use App\Enums\Calidad\ValidacionVentaEnum;
use App\Enums\Calidad\DiccionEnum;
use App\Enums\Calidad\EmpatiaEvaluacionAgenteEnum;
use App\Enums\Calidad\EsperaVaciosEnum;
use App\Enums\Calidad\EscuchaActivaEvaluacionAgenteEnum;
use App\Enums\Calidad\EvitaMaltratoEnum;
use App\Enums\Calidad\AbandonoLlamadaEnum;
use App\Enums\Calidad\LibertyNegativoEnum;
use App\Enums\Calidad\TecnicasDeCierreVentasEnum;
use App\Enums\Calidad\EvitaMaltraAlClientetoEnum;
use App\Enums\Calidad\EvitaMaltratoAlClienteEnum;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Calidad>
 */
class CalidadFactory extends Factory
{

    protected $model = Calidad::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $motivoEvaluacion = $this->faker->randomElement(MotivoEvaluacionEnum::toArray());
        if ($motivoEvaluacion == 'Venta') {
            $ventaLineaId = VentaLinea::all()->random()->id;
            $ventaLineaVentas_telefono = $ventaLineaId;
            // pluck user_id, clientes_id from VentaLinea, then get random user_id from Users table
            $agente = VentaLinea::where('id', $ventaLineaId)->get()->user_id;
            $clienteID = VentaLinea::where('id', $ventaLineaId)->get()->clientes_id;
            if (VentaLinea::where('id', $ventaLineaId)->get()->tlf_marcado != null) {
               $tlf = VentaLinea::where('id', $ventaLineaId)->get()->tlf_marcado;
            } else {
                $tlf = VentaLinea::where('id', $ventaLineaId)->get()->tlf;
            }


        } else {
            $agente = function() {
                $rolePercentages = [
                    'Vendedor' => 95, // 95%
                    'Supervisor' => 3, // 3%
                    'Gerente' => 2, // 2%
                ];
            
                $totalUsers = User::count();
                $roleCounts = [];
            
                foreach ($rolePercentages as $role => $percentage) {
                    $roleCounts[$role] = (int) ($totalUsers * ($percentage / 100));
                }
            
                $role = array_rand($roleCounts);
                $users = User::where('role', $role)->take($roleCounts[$role])->get();
                $RandomAgente = $users->random()->id;
                return $RandomAgente;
                // $ventaLineaId = null;
                // $ventaLineaVentas_telefono = null;
                // return $users->random()->id;
            };
            $ventaLineaId = null;
            $ventaLineaVentas_telefono = null;
                //Tlf
            // Randomly select 6, 7, or 8
            $prefixTlf = $this->faker->randomElement([6, 7, 8]);
            // Random 7-digit number
            $randomSevenDigitsTlf = $this->faker->numberBetween(1000000, 9999999);
            // Concatenate prefix and random 7 digits
            $tlf = intval($prefixTlf.strval($randomSevenDigitsTlf));

            // $agente = User::all()->random()->id;
        }

        return [
            //  We are going to generate a Calidad factory that will have the same fields as the Calidad model.
            //  We will use the faker class to generate random values for each field.

            //  We will use the randomElement method to select a random value from the MotivoEvaluacionEnum class.
            // Fillable fields
            // 'user_id', // 'user_id' is the auditor
            // 'agente', // 'agente' is the user_id from the users table that is being audited
            // 'fecha_llamada', // 'fecha_llamada' is the date of the call
            // 'dia_hora_inicio', // 'dia_hora_inicio' is the start date and time of the call
            // 'dia_hora_final', // 'dia_hora_final' is the end date and time of the call
            // 'motivo_evaluacion', // MotivoEvaluacionEnum reason for audit
            // 'tlf', // 'tlf' is the phone number to be audited
            // 'venta_lineas_id', // 'venta_lineas_id' is the id of the sale line
            // 'observaciones', // 'observaciones' is the observations made by the auditor
            // 'evaluacion_completa', // 'evaluacion_completa' is a bool the complete evaluation
            /**
             *  Define as fillable field and cast as arrays because they are all enums.
             * 
             * bienvenida
             * empatia
             * sondeo
             * escucha_activa
             * oferta_comercial
             * numero_alternativo
             * aclara_dudas_cliente
             * manejo_objeciones
             * genera_ventas_irregulares
             * aceptacion_servicio
             * tecnicas_cierre
             * utiliza_tecnicas_cierre
             * validacion_venta
             * diccion
             * empatia_evalucion_agente
             * espera_vacios
             * escucha_activa_evaluacion_agente
             * evita_maltrato
             * abandono_llamada
             * liberty_negativo 
             * 
             * 
            */

            // user id where roll is Backoffice 90% of the time or supervisor 7% of the time, gerente 3% of the time
            
            'user_id' => function() {
                $rolePercentages = [
                    'Backoffice' => 90, // 90%
                    'Supervisor' => 7, // 7%
                    'Gerente' => 3, // 3%
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

            'agente' => $agente,
            'motivo_evaluacion' => $motivoEvaluacion,
            'tlf' => $tlf,
            'venta_lineas_id' => $ventaLineaId,
            'ventas_telefono' => $ventaLineaVentas_telefono,
            'evaluacion_completa' => $this->faker->boolean('true', 'false'),
            'observaciones' => $this->faker->text(),
            'bienvenida' => $this->faker->randomElement(BienvenidaEnum::class),
            'empatia' => $this->faker->randomElement(EmpatiaEnum::class),
            'sondeo' => $this->faker->randomElement(SondeoEnum::class),
            'escucha_activa' => $this->faker->randomElement(EscuchaActivaEnum::class),
            'oferta_comercial' => $this->faker->randomElement(OfertaComercialEnum::class),
            'numero_alternativo' => $this->faker->randomElement(SolicitudNumeroAlternativoEnum::class),
            'aclara_dudas_cliente' => $this->faker->randomElement(AclaraDudasClienteEnum::class),
            'manejo_objeciones' => $this->faker->randomElement(ManejoObjecionesEnum::class),
            'genera_ventas_irregulares' => $this->faker->randomElement(GenerarVentasIrregularesEnum::class),
            'aceptacion_servicio' => $this->faker->randomElement(AceptacionServicioEnum::class),
            'tecnicas_cierre' => $this->faker->randomElement(TecnicasDeCierreVentasEnum::class),
            'utiliza_tecnicas_cierre' => $this->faker->randomElement(UtilizaTecnicasCierreEnum::class),
            'validacion_venta' => $this->faker->randomElement(ValidacionVentaEnum::class),
            'diccion' => $this->faker->randomElement(DiccionEnum::class),
            'empatia_evalucion_agente' => $this->faker->randomElement(EmpatiaEnum::class),
            'evita_maltrato' => $this->faker->randomElement(EvitaMaltratoAlClienteEnum::class),
            'espera_vacios' => $this->faker->randomElement(EsperaVaciosEnum::class),
            'escucha_activa_evaluacion_agente' => $this->faker->randomElement(EscuchaActivaEnum::class),
            'abandono_llamada' => $this->faker->randomElement(AbandonoLlamadaEnum::class),
            'liberty_negativo' => $this->faker->randomElement(LibertyNegativoEnum::class),
            'calificacion' => $this->faker->numberBetween(0, 100),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),

        ];     
    }
}
