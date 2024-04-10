<?php

namespace App\Enums\Calidad;

use Filament\Support\Contracts\HasLabel;

/**
 * Evaluación de Bienvenida.
 */
enum BienvenidaEnum: string implements HasLabel
{
    //  Verificar si lo quiero aqui o no
    // No utiliza protocolo de despedida

    case ProtocoloBienvenida = 'No utiliza protocolo de bienvenida';
    case IdentificaNombre = 'No se identifica con nombre y apellido';
    case IdentificaEmpresa = 'No identifica la compañía a la que pertenece';
    case SaludoRobotizado = 'Ofrece saludo robotizado';
    case PresonalizarLlamada = 'No personaliza la llamada';
    case SaludoTarde = 'Despliega el saludo de bienvenida después de los primeros 5 seg de iniciada la llamda';

    public function getLabel(): ?string
    {
        // return $this->name;

        // or
        return match ($this) {
            self::ProtocoloBienvenida => 'No utiliza protocolo de bienvenida',
            self::IdentificaNombre => 'No se identifica con nombre y apellido',
            self::IdentificaEmpresa => 'No identifica la compañía a la que pertenece',
            self::SaludoRobotizado => 'Ofrece saludo robotizado',
            self::PresonalizarLlamada => 'No personaliza la llamada',
            self::SaludoTarde => 'Despliega el saludo de bienvenida después de los primeros 5 seg de iniciada la llamda',

        };
    }

    public static function toValues(): array
{
    return [
        self::ProtocoloBienvenida->value => 5,
        self::IdentificaNombre->value => 5,
        self::IdentificaEmpresa->value => 10,
        self::SaludoRobotizado->value => 20,
        self::PresonalizarLlamada->value => 5,
        self::SaludoTarde->value => 2,
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


    public static function asSelectArray(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->value] = $case->getLabel();
        }
        return $array;
    }
}
