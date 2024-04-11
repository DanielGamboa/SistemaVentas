<?php

namespace App\Enums\Calidad;

use Filament\Support\Contracts\HasLabel;

/**
 * Empatía con el cliente.
 */
enum EscuchaActivaEnum: string implements HasLabel
{

/**
 *
 * ESCUCHA ACTIVA
 *   Hace que el cliente repita información de manera innecesaria
 *   Se encuentra distraído en la llamada
 * 
*/
    case RepetirInformacionnInnecesaria = 'Hace que el cliente repita información de manera innecesaria';
    case Distraido = 'Se encuentra distraído en la llamada';

    public function getLabel(): ?string
    {
        // return $this->name;

        // or
        return match ($this) {
            self::RepetirInformacionnInnecesaria => 'Hace que el cliente repita información de manera innecesaria',
            self::Distraido => 'Se encuentra distraído en la llamada',

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
            self::RepetirInformacionnInnecesaria->value => 20,
            self::Distraido->value => 20,
        ];
    }
}
