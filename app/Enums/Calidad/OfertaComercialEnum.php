<?php

namespace App\Enums\Calidad;

use Filament\Support\Contracts\HasLabel;

/**
 * 
 * OFERTA COMERCIAL
 *   No ofrece la promoción
 *   Ofrece promoción incompleta
 *   Ofrece promoción incorrecta
 *   No menciona el plan a contratar
 *   No menciona el costo del plan
 *   No menciona la cantidad de GB
 *   No menciona las RRSS
 *   No menciona los SMS a Liberty
 *   No menciona los minutos a Liberty
 *   No menciona los minutos a todas las redes CR, Tigo Nicaragua, USA y Canadá
 *   No menciona el beneficio Liberty Sin Fronteras
 *   No menciona beneficios de pasar GB
 *   No menciona la acumulación de los GB
 *   No menciona SMS a Liberty
 *   No menciona el impuesto de cruz roja
 *   No menciona el impuesto de 911
 */
enum OfertaComercialEnum: string implements HasLabel
{
    case NoPromocion = 'No ofrece la promoción';
    case PromocionIncompleta = 'Ofrece promoción incompleta';
    case PromocionIncorrecta = 'Ofrece promoción incorrecta';
    case NoMencionaPlan = 'No menciona el plan a contratar';
    case NoMencionaCosto = 'No menciona el costo del plan';
    case NoMencionaCantidadGB = 'No menciona la cantidad de GB';
    case NoMencionaRSS = 'No menciona las redes sociales';
    case NoMencionaSMS = 'No menciona los SMS a Liberty';
    case NoMencionaMinutos = 'No menciona los minutos a Liberty';
    case MinutosOtrasOperadoras = 'No menciona los minutos a todas las redes CR, Tigo Nicaragua, USA y Canadá';
    case NoMencionaLibertySinFronteras = 'No menciona el beneficio Liberty Sin Fronteras';
    case NoMencionaPasaGB = 'No menciona beneficios de pasar GB';
    case NoMencionaAcumulaGB = 'No menciona la acumulación de los GB';
    case NoMencionaSMSLiberty = 'No menciona SMS a othas operadoras';
    case NoMencionaImpuestoCruzRoja = 'No menciona el impuesto de cruz roja';
    case NoMencionaImpuestoNineOneOne = 'No menciona el impuesto de 911';

    public function getLabel(): ?string
    {
        // return $this->name;

        // or
        return match ($this) {
            self::NoPromocion => 'No ofrece la promoción',
            self::PromocionIncompleta => 'Ofrece promoción incompleta',
            self::PromocionIncorrecta => 'Ofrece promoción incorrecta',
            self::NoMencionaPlan => 'No menciona el plan a contratar',
            self::NoMencionaCosto => 'No menciona el costo del plan',
            self::NoMencionaCantidadGB => 'No menciona la cantidad de GB',
            self::NoMencionaRSS => 'No menciona las redes sociales',
            self::NoMencionaSMS => 'No menciona los SMS a Liberty',
            self::NoMencionaMinutos => 'No menciona los minutos a Liberty',
            self::MinutosOtrasOperadoras => 'No menciona los minutos a todas las redes CR, Tigo Nicaragua, USA y Canadá',
            self::NoMencionaLibertySinFronteras => 'No menciona el beneficio Liberty Sin Fronteras',
            self::NoMencionaPasaGB => 'No menciona beneficios de pasar GB',
            self::NoMencionaAcumulaGB => 'No menciona la acumulación de los GB',
            self::NoMencionaSMSLiberty => 'No menciona SMS a otras operadoras',
            self::NoMencionaImpuestoCruzRoja => 'No menciona el impuesto de cruz roja',
            self::NoMencionaImpuestoNineOneOne => 'No menciona el impuesto de 911',
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
