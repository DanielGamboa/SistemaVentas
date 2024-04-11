<?php

namespace App\Enums\Calidad;

use Filament\Support\Contracts\HasLabel;

/**
 * EVITA ABANDONO / LIBERACION DE LA LLAMADA
 *    Abandono de la llamada
 *    Liberación la llamada
 *    Excede el tiempo de espera y el cliente finaliza la llamada
 */
enum LibertyNegativoEnum: string implements HasLabel
{
    //  Verificar si lo quiero aqui o no
    // No utiliza protocolo de despedida

    case PersuadirOpinionNegativa = 'No busca cambiar la opinion negativa del cliente con respecto del servicio o la compañía';

    public function getLabel(): ?string
    {
        // return $this->name;

        // or
        return match ($this) {
            self::PersuadirOpinionNegativa => 'No busca cambiar la opinion negativa del cliente con respecto del servicio o la compañía',
        };
    }

    // To value
    public static function toValues(): array
    {
        return [
            self::PersuadirOpinionNegativa->value => 20,
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
