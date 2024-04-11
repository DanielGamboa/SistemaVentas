<?php

namespace App\Enums\Calidad;

use Filament\Support\Contracts\HasLabel;

/**
 * 
 * ACLARA DUDAS DEL CLIENTE DE MANERA CORRECTA
 *     No se muestra dispuesto ayudar y ofrecer una solución a las necesidades del cliente
 *     No aclara las dudas del cliente
 *     No aclara las dudas del cliente de manera correcta
 *     No explica proceso de facturación correctamente
 *     Brinda información incompleta/incorrecta
 * 
 */
enum AclaraDudasClienteEnum: string implements HasLabel
{

    case IndispuestoAyudar = 'No se muestra dispuesto ayudar y ofrecer una solución a las necesidades del cliente';
    case NoAclaraDudas = 'No aclara las dudas del cliente';
    case NoDaInformacionCorrecta = 'No aclara las dudas del cliente de manera correcta';
    case NoExplicaFacturacion = 'No explica proceso de facturación correctamente';
    case InformacionIncompletaIncorrecta = 'Brinda información incompleta/incorrecta';

    public function getLabel(): ?string
    {
        // return $this->name;

        // or
        return match ($this) {
            self::IndispuestoAyudar => 'No se muestra dispuesto ayudar y ofrecer una solución a las necesidades del cliente',
            self::NoAclaraDudas => 'No aclara las dudas del cliente',
            self::NoDaInformacionCorrecta => 'No aclara las dudas del cliente de manera correcta',
            self::NoExplicaFacturacion => 'No explica proceso de facturación correctamente',
            self::InformacionIncompletaIncorrecta => 'Brinda información incompleta/incorrecta',
        };
    }

    // To Values
    public static function toValues(): array
    {
        return [
            self::IndispuestoAyudar->value => 100,
            self::NoAclaraDudas->value => 100,
            self::NoDaInformacionCorrecta->value => 100,
            self::NoExplicaFacturacion->value => 100,
            self::InformacionIncompletaIncorrecta->value => 100,
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
