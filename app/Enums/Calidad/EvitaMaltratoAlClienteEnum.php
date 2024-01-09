<?php

namespace App\Enums\Calidad;

use Filament\Support\Contracts\HasLabel;

/**
 * 
 * EVITA MALTRATO AL CLIENTE
 *    Utiliza lenguaje soez
 *    Mantiene un trato irrespetuoso utilizando expresiones o gestos que denoten burla
 *    Intenta dominar la situación de forma grosera o impositiva
 *    Interrumpe al cliente de manera grosera
 *    No permite que el cliente se exprese
 *    Mantiene un trato irrespetuoso utilizando expresiones o gestos que denoten tedio 
 */
enum EvitaMaltratoAlClienteEnum: string implements HasLabel
{
    //  Verificar si lo quiero aqui o no
    // No utiliza protocolo de despedida

    case LenguajeSoez = 'Utiliza lenguaje soez';
    case TratoIrrespetuoso = 'Mantiene un trato irrespetuoso utilizando expresiones o gestos que denoten burla';
    case DominaGrosero = 'Intenta dominar la situación de forma grosera o impositiva';
    case InterrumpeCliente = 'Interrumpe al cliente de manera grosera';
    case ImpideCliente = 'No permite que el cliente se exprese';
    case TratoTedioso = 'Mantiene un trato irrespetuoso utilizando expresiones o gestos que denoten tedio ';

    public function getLabel(): ?string
    {
        // return $this->name;

        // or
        return match ($this) {
            self::LenguajeSoez => 'Utiliza lenguaje soez',
            self::TratoIrrespetuoso => 'Mantiene un trato irrespetuoso utilizando expresiones o gestos que denoten burla',
            self::DominaGrosero => 'Intenta dominar la situación de forma grosera o impositiva',
            self::InterrumpeCliente => 'Interrumpe al cliente de manera grosera',
            self::ImpideCliente => 'No permite que el cliente se exprese',
            self::TratoTedioso => 'Mantiene un trato irrespetuoso utilizando expresiones o gestos que denoten tedio ',
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
