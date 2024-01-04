<?php
    
namespace App\Enums\Calidad;
use Filament\Support\Contracts\HasLabel;
    
    /**
     * Motivo de la Evaluación.
     */
    
enum MotivoEvaluacionEnum:string implements HasLabel
{    
  //  Verificar si lo quiero aqui o no
  // No utiliza protocolo de despedida

    case  Agente = 'Agente';
    case  Aleatorio = 'Aleatorio';
    case  Gerencia = 'Gerencia';
    case  SolicitudLiberty = 'Solicitud Liberty';
    case  Venta = 'Venta';

    public function getLabel(): ?string
    {
        // return $this->name;
        
        // or
        return match ($this) {
            self::Agente =>'Agente',
            self::Aleatorio =>'Aleatorio',
            self::Gerencia =>'Gerencia',
            self::SolicitudLiberty =>'Solicitud Liberty',
            self::Venta =>'Venta',
        };
    }
}