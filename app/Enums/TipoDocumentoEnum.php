<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;


/**
 * Nombre de los Planes de liberty.
 */
enum TipoDocumentoEnum: string implements HasLabel
{
    case Cedula = 'Cedula';
    case Dimex = 'Dimex';
    case Pasaporte = 'Pasaporte';
    case Refugiado = 'Refugiado';

    public function getLabel(): ?string
    {
        // return $this->name;

        // or

        return match ($this) {
            self::Cedula => 'Cedula',
            self::Dimex => 'Dimex',
            self::Pasaporte => 'Pasaporte',
            self::Refugiado => 'Refugiado',
        };
    }

}
