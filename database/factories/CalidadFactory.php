<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Calidad;
use App\Models\User;
use App\Enums\Calidad\MotivoEvaluacionEnum;
use App\Models\VentaLinea;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Calidad>
 */
class CalidadFactory extends Factory
{
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
            // pluck user_id, clientes_id from VentaLinea, then get random user_id from Users table
            $agente = VentaLinea::where('id', $ventaLineaId)->get()->user_id;
            $clienteID = VentaLinea::where('id', $ventaLineaId)->get()->clientes_id;
            if (VentaLinea::where('id', $ventaLineaId)->get()->tlf_marcado != null) {
               $tlf = VentaLinea::where('id', $ventaLineaId)->get()->tlf_marcado;
            } else {
                $tlf = VentaLinea::where('id', $ventaLineaId)->get()->tlf;
            }
        };
        
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

            // Select a random MotivoEvaluacionEnum value
            'motivo_evaluacion' => $this->faker->randomElement(MotivoEvaluacionEnum::toArray()),
            // Depending on motivo_evaluacion, values will be generated for the following fields:
                // If motivo_evaluacion is Agente, Aleatorio, Gerencia, Solicitud Liberty. Then
            if ($this->faker->randomElement(MotivoEvaluacionEnum::toArray()) == 'Agente' || 'Aleatorio' || 'Gerencia' || 'Solicitud Liberty') {
                // Select a random user_id from the Users table where the role is Agente
                'agente' => $this->faker->randomElement(User::where('role', 'Agente')->get()->pluck('id')->toArray()),
                // Select a random date
                'fecha_llamada' => $this->faker->date(),
                // Select a random date and time
                'dia_hora_inicio' => $this->faker->dateTimeBetween('-1 year', 'now'),
                // Select a random date and time
                'dia_hora_final' => $this->faker->dateTimeBetween('-1 year', 'now'),
                // Randomly select 6, 7, or 8
                $prefixTlf = $this->faker->randomElement([6, 7, 8]);
                // Random 7-digit number
                $randomSevenDigitsTlf = $this->faker->numberBetween(1000000, 9999999);
                // Concatenate prefix and random 7 digits
                $tlf = intval($prefixTlf.strval($randomSevenDigitsTlf));
                'tlf' => $tlf,
            }
            // If motivo_evaluacion is Venta. Then get random venta linea from venta_lineas table

    }
}
