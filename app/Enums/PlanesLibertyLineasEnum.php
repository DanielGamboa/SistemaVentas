<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Nombre de los Planes de liberty.
 */
enum PlanesLibertyLineasEnum: string implements HasLabel
{
    case Plan1 = 'Plan @1';
    case Plan1Plus = 'Plan @1 Plus';
    case Plan2 = 'Plan @2';
    case Plan3 = 'Plan @3';
    case Plan4 = 'Plan @4';
    case Plan5 = 'Plan @5';
    case Plan6 = 'Plan @6';

    public function getLabel(): ?string
    {
        // return $this->name;

        // or

        return match ($this) {
            self::Plan1 => 'Plan @1',
            self::Plan1Plus => 'Plan @1 Plus',
            self::Plan2 => 'Plan @2',
            self::Plan3 => 'Plan @3',
            self::Plan4 => 'Plan @4',
            self::Plan5 => 'Plan @5',
            self::Plan6 => 'Plan @6',
        };
    }
    // convert enum to array
    public static function toArray(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->name] = $case->value;
        }
        return $array;
    }
}

// enum PlanesLibertyLineasEnum:string {
//     case Plan1 = 'Plan @1';
//     case Plan1Pluss = 'Plan @1 Pluss';
//     case Plan2 = 'Plan @2';
//     case Plan3 = 'Plan @3';
//     case Plan4 = 'Plan @4';
//     case Plan5 = 'Plan @5';
//     case Plan6 = 'Plan @6';

// }
