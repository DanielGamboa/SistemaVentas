<?php
    
namespace App\Enums\Calidad;
use Filament\Support\Contracts\HasLabel;
    
    /**
     * Empatía con el cliente.
     */
    
enum EmpatiaEnum:string implements HasLabel
{    

  
  // No genera empatía con el cliente
  // No se pone en lugar del cliente ni compreden su situación
  // Trata temas con terceros fuera de la gestión

    case  EmpatiaCliente = 'No genera empatía con el cliente';
    case  LugarCliente = 'No se pone en lugar del cliente ni compreden su situación';
    case  Terceros = 'Trata temas con terceros fuera de la gestión';
    
    public function getLabel(): ?string
    {
        // return $this->name;
        
        // or
        return match ($this) {
            self::EmpatiaCliente =>'No utiliza protocolo de bienvenida',
            self::LugarCliente =>'No se identifica con nombre y apellido',
            self::Terceros =>'No identifica la compañía a la que pertenece',
            
        };
    }
}