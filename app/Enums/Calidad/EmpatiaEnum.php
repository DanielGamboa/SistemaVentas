<?php

namespace App\Enums\Calidad;

use Filament\Support\Contracts\HasLabel;

/**
 * Empatía con el cliente.
 */
enum EmpatiaEnum: string implements HasLabel
{


    case EmpatiaCliente = 'No genera empatía con el cliente';
    case LugarCliente = 'No se pone en lugar del cliente ni compreden su situación';
    case Terceros = 'Trata temas con terceros fuera de la gestión';

    public function getLabel(): ?string
    {
        // return $this->name;

        // or
        return match ($this) {
            self::EmpatiaCliente => 'No genera empatía con el cliente',
            self::LugarCliente => 'No se pone en lugar del cliente ni compreden su situación',
            self::Terceros => 'Trata temas con terceros fuera de la gestión',

        };
    }

    public static function toValues(): array
    {
        return [
            self::EmpatiaCliente->value => 10,
            self::LugarCliente->value => 5,
            self::Terceros->value => 20,
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
