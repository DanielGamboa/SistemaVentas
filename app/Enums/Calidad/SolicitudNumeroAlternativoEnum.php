<?php

namespace App\Enums\Calidad;

use Filament\Support\Contracts\HasLabel;

/**
 * SOLICITUD DE NUMERO DE CONTACTO ALTERNO
 *   No solicita un segundo número de contacto
 *   No solicita número a portar
 *   No valida número a portar
 */
enum SolicitudNumeroAlternativoEnum: string implements HasLabel
{


    case SegundoNumeroContacto = 'No solicita un segundo número de contacto';
    case NoSolicitaNumeroPortar = 'No solicita número a portar';
    case NoValidaNumeroPortar = 'No valida número a portar';
    
    public function getLabel(): ?string
    {
        // return $this->name;

        // or
        return match ($this) {
            self::SegundoNumeroContacto => 'No solicita un segundo número de contacto',
            self::NoSolicitaNumeroPortar => 'No solicita número a portar',
            self::NoValidaNumeroPortar => 'No valida número a portar',
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
