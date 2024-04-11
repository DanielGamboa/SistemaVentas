<?php

namespace App\Enums\Calidad;

use Filament\Support\Contracts\HasLabel;

/**
 * 
 * MANEJO DE OBJECIONES
 *    No hace uso de sus herramientas de manejo de objeciones.
 *    No hace intentos para rebatir las objeciones al menos en dos (3) oportunidades
 *    Reacciona ante las objeciones contraatacando al cliente
 *    Se inventa una respuesta que no es real a la objeción del cliente
 *    Se rinde aceptando la objeción del cliente a la primera de cambio 
 */
enum ManejoObjecionesEnum: string implements HasLabel
{

    case NoUsaHerramientasObjeciones = 'No hace uso de sus herramientas de manejo de objeciones';
    case NoIntentaRebatir = 'No hace intentos para rebatir las objeciones al menos en tres (3) oportunidades';
    case ContraatacaCliente = 'Reacciona ante las objeciones contraatacando al cliente';
    case InventaRespuestaFalsa = 'Se inventa una respuesta que no es real a la objeción del cliente';
    case AceptaObjecion = 'Se rinde aceptando la objeción del cliente a la primera de cambio ';

    public function getLabel(): ?string
    {
        // return $this->name;

        // or
        return match ($this) {
            self::NoUsaHerramientasObjeciones => 'No hace uso de sus herramientas de manejo de objeciones',
            self::NoIntentaRebatir => 'No hace intentos para rebatir las objeciones al menos en tres (3) oportunidades',
            self::ContraatacaCliente => 'Reacciona ante las objeciones contraatacando al cliente',
            self::InventaRespuestaFalsa => 'Se inventa una respuesta que no es real a la objeción del cliente',
            self::AceptaObjecion => 'Se rinde aceptando la objeción del cliente a la primera de cambio ',
        };
    }

    // To value
    public static function toValues(): array
    {
        return [
            self::NoUsaHerramientasObjeciones->value => 20,
            self::NoIntentaRebatir->value => 20,
            self::ContraatacaCliente->value => 20,
            self::InventaRespuestaFalsa->value => 100,
            self::AceptaObjecion->value => 20,
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
