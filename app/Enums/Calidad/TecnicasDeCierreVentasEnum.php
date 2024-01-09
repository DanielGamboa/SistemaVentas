<?php

namespace App\Enums\Calidad;

use Filament\Support\Contracts\HasLabel;

/**
 * 
 * UTILIZA TECNICAS DE CIERRE PARA CONCRETAR VENTAR LA VENTA
 *      No realiza el cierre en el momento oportuno (después de captar el interés del cliente
 *      No muestra una actitud positiva, demostrando que se ha llegado a un acuerdo con el cliente
 *      No cierra la venta con firmeza
 *      Genera preguntas abiertas
 *      Genera preguntas negativas
 *      Realiza cierre con oferta engañosa
 * 
 */
enum TecnicasDeCierreVentasEnum: string implements HasLabel
{

    case NoCierraOportunamente = 'No realiza el cierre en el momento oportuno (después de captar el interés del cliente';
    case ActitudNegativa = 'No muestra una actitud positiva, demostrando que se ha llegado a un acuerdo con el cliente';
    case NoCierraFirme = 'No cierra la venta con firmeza';
    case PreguntasAbiertas = 'Genera preguntas abiertas';
    case PreguntaNegativa = 'Genera preguntas negativas';
    case CierreFraudulento = 'Realiza cierre con oferta engañosa';

    public function getLabel(): ?string
    {
        // return $this->name;

        // or
        return match ($this) {
            self::NoCierraOportunamente => 'No realiza el cierre en el momento oportuno (después de captar el interés del cliente',
            self::ActitudNegativa => 'No muestra una actitud positiva, demostrando que se ha llegado a un acuerdo con el cliente',
            self::NoCierraFirme => 'No cierra la venta con firmeza',
            self::PreguntasAbiertas => 'Genera preguntas abiertas',
            self::PreguntaNegativa => 'Genera preguntas negativas',
            self::CierreFraudulento => 'Realiza cierre con oferta engañosa',
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
