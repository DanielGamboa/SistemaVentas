<?php

namespace App\Enums\Calidad;

use Filament\Support\Contracts\HasLabel;

/**
 * 
 * PROSPECCION O DETERMINACION DE NECESIDADES (SONDEO)
 *   No genera sondeo con el clte para determinar las necesidades y lograr la venta
 *   No tiene un argumento sólido que logre mantener la atención del cliente
 *   No muestra confianza durante el proceso de negociación
 *   No sondea dudas referente al servicio ofrecido o contratado
 *   No sondea horario para comunicarse en otro momento
 */
enum SondeoEnum: string implements HasLabel
{
    case NoSondea = 'No genera sondeo con el cliente para determinar las necesidades y lograr la venta';
    case MalaArgumentacion = 'No tiene un argumento sólido que logre mantener la atención del cliente';
    case FaltaConfianza = 'No muestra confianza durante el proceso de negociación';
    case SondeoDudas = 'No sondea dudas referente al servicio ofrecido o contratado';
    case SondeoHorario = 'No sondea horario para comunicarse en otro momento';
    
    public function getLabel(): ?string
    {
        // return $this->name;

        // or
        return match ($this) {
            self::NoSondea => 'No genera sondeo con el clte para determinar las necesidades y lograr la venta',
            self::MalaArgumentacion => 'No tiene un argumento sólido que logre mantener la atención del cliente',
            self::FaltaConfianza => 'No muestra confianza durante el proceso de negociación',
            self::SondeoDudas => 'No sondea dudas referente al servicio ofrecido o contratado',
            self::SondeoHorario => 'No sondea horario para comunicarse en otro momento',
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

    // To value
    public static function toValues(): array
    {
        return [
            self::NoSondea->value => 20,
            self::MalaArgumentacion->value => 20,
            self::FaltaConfianza->value => 20,
            self::SondeoDudas->value => 20,
            self::SondeoHorario->value => 20,
        ];
    }
}
