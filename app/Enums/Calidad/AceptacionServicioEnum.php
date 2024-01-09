<?php

namespace App\Enums\Calidad;

use Filament\Support\Contracts\HasLabel;

/**
 * Empatía con el cliente.
 */
enum AceptacionServicioEnum: string implements HasLabel
{

/**
 * 
 * ACEPTACION DEL SERVICIO POR PARTE DEL CLIENTE
 *   No confirma con el cliente si acepta el servicio.
 *   No valida aceptación con el titular
 * 
*/
    case NoConfirmaAceptacion = 'No confirma con el cliente si acepta el servicio';
    case TitularNoValidaAceptacion = 'No valida aceptación con el titular';

    public function getLabel(): ?string
    {
        // return $this->name;

        // or
        return match ($this) {
            self::NoConfirmaAceptacion => 'No confirma con el cliente si acepta el servicio',
            self::TitularNoValidaAceptacion => 'No valida aceptación con el titular',

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
