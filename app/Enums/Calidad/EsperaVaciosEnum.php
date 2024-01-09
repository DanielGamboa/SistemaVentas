<?php

namespace App\Enums\Calidad;

use Filament\Support\Contracts\HasLabel;

/**
 * Empatía con el cliente.
 */
enum EsperaVaciosEnum: string implements HasLabel
{

/**
 *
 * CLIENTE EN ESPERA / USO DE ESPACIOS VACIOS
 *  No utiliza protocolo para colocar al cliente en espera
 *  Excede los tiempos de espera (45 seg)
 *  No utiliza protocolo para retomar la llamada
 *  Deja espacios vacios durante la llamada (15 seg. o más)
 * 
*/
    case ProtocoloEspera = 'No utiliza protocolo para colocar al cliente en espera';
    case ExcedeEspera = 'Excede los tiempos de espera (45 seg)';
    case Terceros = 'Trata temas con terceros fuera de la gestión';
    case Vacios = 'Deja espacios vacios durante la llamada (15 seg. o más)';

    public function getLabel(): ?string
    {
        // return $this->name;

        // or
        return match ($this) {
            self::ProtocoloEspera => 'No utiliza protocolo para colocar al cliente en espera',
            self::ExcedeEspera => 'Excede los tiempos de espera (45 seg)',
            self::Terceros => 'No utiliza protocolo para retomar la llamada',
            self::Vacios => 'Deja espacios vacios durante la llamada (15 seg. o más)',

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
