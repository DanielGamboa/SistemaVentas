<?php
    
namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
    
    /**
     * Nombre de los Planes de liberty.
     */
    
enum ImagenesDocumentoEnum:string implements HasLabel
{    

    case  CedulaAmberso = 'Cedula Amberso';
    case  CedulaReverso = 'Cedula Reverso';
    case  DimexAmberso = 'Dimex Amberso';
    case  DimexReverso = 'Dimex Reverso';
    case  Pasaporte = 'Pasaporte';


    public function getLabel(): ?string
    {
        // return $this->name;
        
        // or
        return match ($this) {
            self::CedulaAmberso =>'Cedula Amberso',
            self::CedulaReverso =>'Cedula Reverso',
            self::DimexAmberso =>'Dimex Amberso',
            self::DimexReverso =>'Dimex Reverso',
            self::Pasaporte =>'Pasaporte',
        };
    }
}