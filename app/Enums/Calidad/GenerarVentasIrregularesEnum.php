<?php

namespace App\Enums\Calidad;

use Filament\Support\Contracts\HasLabel;

/**
 * Generar Ventas Irregulares.
 * 
 *   Realiza venta incompleta
 *   Realiza venta impuesta (sin aceptación o autorización del cliente)
 *   Ofrece productos adicionales que no están establecidos en la campaña para lograr
 *   Manipula información otorgada por el cliente para lograr la venta
 * 
 */
enum GenerarVentasIrregularesEnum: string implements HasLabel
{
    case VentaIncompleta = 'Realiza venta incompleta';
    case VentaImpuesta = 'Realiza venta impuesta (sin aceptación o autorización del cliente)';
    case VentaFraudulenta = 'Ofrece productos adicionales que no están establecidos en la campaña para lograr';
    case ManipulaInformacion = 'Manipula información otorgada por el cliente para lograr la venta';

    public function getLabel(): ?string
    {
        // return $this->name;

        // or
        return match ($this) {
            self::VentaIncompleta => 'Realiza venta incompleta',
            self::VentaImpuesta => 'Realiza venta impuesta (sin aceptación o autorización del cliente)',
            self::VentaFraudulenta => 'Ofrece productos adicionales que no están establecidos en la campaña para lograr',
            self::ManipulaInformacion => 'Manipula información otorgada por el cliente para lograr la venta',
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
