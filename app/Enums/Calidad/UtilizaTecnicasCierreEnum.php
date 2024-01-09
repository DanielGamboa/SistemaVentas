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

    case Agente = 'Registra datos de la venta incompletos';
    case Aleatorio = 'Registra datos de la venta incorrectos y/o falsos';
    case Gerencia = 'Gerencia';
    case SolicitudLiberty = 'Solicitud Liberty';
    case Venta = 'Venta';

    public function getLabel(): ?string
    {
        // return $this->name;

        // or
        return match ($this) {
            self::Agente => 'Registra datos de la venta incompletos',
            self::Aleatorio => 'Registra datos de la venta incorrectos y/o falsos',
            self::Gerencia => 'No realiza registro de los datos',
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
