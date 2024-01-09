<?php

namespace App\Enums;

/**
 * Enum Precio de planes liberty para ser almacenado en la base de datos.
 */
enum PreciosPlanesLibertyLineasEnum: string
{
    case Plan1 = '10700';
    case Plan1Pluss = '13700';
    case Plan2 = '16000';
    case Plan3 = '22000';
    case Plan4 = '27200';
    case Plan5 = '33200';
    case Plan6 = '42200';

    public function getLabel(): ?int
    {
        // return $this->name;

        // or

        return match ($this) {
            self::Plan1 => '10700',
            self::Plan1Pluss => '13700',
            self::Plan2 => '16000',
            self::Plan3 => '22000',
            self::Plan4 => '27200',
            self::Plan5 => '33200',
            self::Plan6 => '42200',
        };
    }
}
