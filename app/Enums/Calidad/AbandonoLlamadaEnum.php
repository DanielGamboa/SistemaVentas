<?php

namespace App\Enums\Calidad;

use Filament\Support\Contracts\HasLabel;

/**
 * EVITA ABANDONO / LIBERACION DE LA LLAMADA
 *    Abandono de la llamada
 *    Liberación la llamada
 *    Excede el tiempo de espera y el cliente finaliza la llamada
 */
enum AbandonoLlamadaEnum: string implements HasLabel
{
    //  Verificar si lo quiero aqui o no
    // No utiliza protocolo de despedida

    case AbandonoLlamada = 'Abandono de la llamada';
    case LiberaLlamada = 'Liberación la llamada';
    case ExcedeTiempoEspera = 'Excede el tiempo de espera y el cliente finaliza la llamada';

    public function getLabel(): ?string
    {
        // return $this->name;

        // or
        return match ($this) {
            self::AbandonoLlamada => 'Abandono de la llamada',
            self::LiberaLlamada => 'Liberación la llamada',
            self::ExcedeTiempoEspera => 'Excede el tiempo de espera y el cliente finaliza la llamada',
        };
    }

    // To value
    public static function toValues(): array
    {
        return [
            self::AbandonoLlamada->value => 10,
            self::LiberaLlamada->value => 10,
            self::ExcedeTiempoEspera->value => 20,
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
