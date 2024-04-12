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
    case TratoIrrespetuoso = 'Mantiene un trato irrespetuoso utilizando expresiones o gestos que denoten burla o tedio';
    case DominaGrosero = 'Intenta dominar la situación de forma grosera o impositiva';
    case InterrumpeCliente = 'Interrumpe al cliente de manera grosera';
    case ImpideCliente = 'No permite que el cliente se exprese';
    

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
            
        };
    }

    // To values
    public static function toValues(): array
    {
        return [
            self::LenguajeSoez->value => 20,
            self::TratoIrrespetuoso->value => 100,
            self::DominaGrosero->value => 100,
            self::InterrumpeCliente->value => 50,
            self::ImpideCliente->value => 100,
        ];
    }

    // to array
    public static function toArray(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->name] = $case->value;
        }
        return $array;
    }
}
