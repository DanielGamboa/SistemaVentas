<?php

namespace App\Enums\Calidad;

use Filament\Support\Contracts\HasLabel;

/**
 * Modulación, dicción, tono de voz y cortesía.
 */
enum DiccionEnum: string implements HasLabel
{
    case Pronunciacion = 'No pronuncia correctamente las palabras';
    case Muletillas = 'Hace uso de muletillas (se apoya en expresiones como: Ok, correcto, de acuerdo)';
    case Interjecciones = 'Hace uso de interjecciones (se apoya en expresiones como: Ummm, Ammm, Ehh)';
    case Monotono = 'Tiene un tono de voz monótono';
    case LecturaFluida = 'No genera lectura de manera fluida';
    case LenguajeColoquial = 'Utiliza lenguaje o expresiones coloquiales informales';
    case GestionRobotizada = 'Realiza gestión robotizada';
    case RitmoConversacion = 'No mantener un ritmo de conversación adecuado';
    case TuteaCliente = 'Tutea al cliente';
    case Disculpas = 'No ofrece disculpas cuando es necesario';
    case Inseguridad = 'Muestra inseguridad al momento de brindar la información';

    public function getLabel(): ?string
    {
        // return $this->name;

        // or
        return match ($this) {
            self::Pronunciacion => 'No pronuncia correctamente las palabras',
            self::Muletillas => 'Hace uso de muletillas (se apoya en expresiones como: Ok, correcto, de acuerdo)',
            self::Interjecciones => 'Hace uso de interjecciones (se apoya en expresiones como: Ummm, Ammm, Ehh)',
            self::Monotono => 'Tiene un tono de voz monótono',
            self::LecturaFluida => 'No genera lectura de manera fluida',
            self::LenguajeColoquial => 'Utiliza lenguaje o expresiones coloquiales informales',
            self::GestionRobotizada => 'Realiza gestión robotizada',
            self::RitmoConversacion => 'No mantener un ritmo de conversación adecuado',
            self::TuteaCliente => 'Tutea al cliente',
            self::Disculpas => 'No ofrece disculpas cuando es necesario',
            self::Inseguridad => 'Muestra inseguridad al momento de brindar la información',
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