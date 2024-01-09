<?php

namespace App\Enums\Calidad;

use Filament\Support\Contracts\HasLabel;

/**
 * SOLICITUD DE NUMERO DE CONTACTO ALTERNO
 * 
 * REALIZA VALIDACION DE LA VENTA
 *   No genera la validación de la venta
 *   No genera la validación de la venta de forma completa
 */
enum ValidacionVentaEnum: string implements HasLabel
{


    case NoValidaVenta = 'No genera la validación de la venta';
    case ValidacionIncompleta = 'No genera la validación de la venta de forma completa';
    
    public function getLabel(): ?string
    {
        // return $this->name;

        // or
        return match ($this) {
            self::NoValidaVenta => 'No genera la validación de la venta',
            self::ValidacionIncompleta => 'No genera la validación de la venta de forma completa',
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
