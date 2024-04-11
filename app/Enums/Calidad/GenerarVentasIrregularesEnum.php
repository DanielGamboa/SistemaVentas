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
    case NoSondeaDudas = 'No sondea dudas referente al servicio ofrecido o contratado';

    public function getLabel(): ?string
    {
        // return $this->name;

        // or
        return match ($this) {
            self::VentaIncompleta => 'Realiza venta incompleta',
            self::VentaImpuesta => 'Realiza venta impuesta (sin aceptación o autorización del cliente)',
            self::VentaFraudulenta => 'Ofrece productos adicionales que no están establecidos en la campaña para lograr',
            self::ManipulaInformacion => 'Manipula información otorgada por el cliente para lograr la venta',
            self::NoSondeaDudas => 'No sondea dudas referente al servicio ofrecido o contratado',
        };
    }

    // To Values
    public static function toValues(): array
    {
        return [
            self::VentaIncompleta->value => 100,
            self::VentaImpuesta->value => 100,
            self::VentaFraudulenta->value => 100,
            self::ManipulaInformacion->value => 100,
            self::NoSondeaDudas->value => 30,
        ];
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
