<?php
    
namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
    
    /**
     * Nombre de los Planes de liberty.
     */
    
enum EstatusVentaLineaEnum:string implements HasLabel
{    

    case  EvaluacionCrediticia = 'Evaluación Crediticia';
    case  Aplica = 'Aplica';
    case  AplicaReconsideracion = 'Aplica reconsideración';
    case  Desconectado = 'Desconectado';
    case  EnviadoReconsideracion = 'Enviado a reconsideración';
    case  Negado = 'Negado';
    case  NegadoReconsideracion = 'Negado reconsideración';

    public function getLabel(): ?string
    {
        // return $this->name;
        
        // or
        return match ($this) {
            self::EvaluacionCrediticia =>'Evaluación Crediticia',
            self::Aplica =>'Aplica',
            self::AplicaReconsideracion =>'Aplica reconsideración',
            self::Desconectado =>'Desconectado',
            self::EnviadoReconsideracion =>'Enviado a reconsideración',
            self::Negado =>'Negado',
            self::NegadoReconsideracion =>'Negado reconsideración',

        };
    }
}