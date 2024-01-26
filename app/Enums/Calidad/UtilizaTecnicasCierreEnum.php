<?php

namespace App\Enums\Calidad;

use Filament\Support\Contracts\HasLabel;

/**
 * UTILIZA TECNICAS DE CIERRE PARA CONCRETAR LA VENTA
 *    Registra datos de la venta incompletos
 *    Registra datos de la venta incorrectos y/o falsos
 *    No realiza registro de los datos
 * 
 * 
 */
enum UtilizaTecnicasCierreEnum: string implements HasLabel
{

    case RegistraDatosIncompletos = 'Registra datos de la venta incompletos';
    case DatosIncorrectosFalsos = 'Registra datos de la venta incorrectos y/o falsos';
    case NoRegistraDatos = 'No realiza registro de los datos';


    public function getLabel(): ?string
    {
        // return $this->name;

        // or
        return match ($this) {
            self::RegistraDatosIncompletos => 'Registra datos de la venta incompletos',
            self::DatosIncorrectosFalsos => 'Registra datos de la venta incorrectos y/o falsos',
            self::NoRegistraDatos => 'No realiza registro de los datos',
        };
    }
    public static function toArray(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->name] = $case->value;
        }
        return $array;
    }
}
