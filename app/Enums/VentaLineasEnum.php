<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum VentaLineasEnum: string implements HasLabel
{
    case LineaNueva = 'Linea Nueva';
    case Migracion = 'Migracion';
    case Portabilidad = 'Portabilidad';

    public function getLabel(): ?string
    {
        // return $this->name;

        // or

        return match ($this) {
            self::LineaNueva => 'Linea Nueva',
            self::Migracion => 'Migracion',
            self::Portabilidad => 'Portabilidad',
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
