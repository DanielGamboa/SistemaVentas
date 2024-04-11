<?php

namespace App\Filament\Resources\CalidadResource\Pages;

use App\Filament\Resources\CalidadResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\User;
// List Enums
use App\Enums\Calidad\BienvenidaEnum;
use App\Enums\Calidad\EmpatiaEnum;
use App\Enums\Calidad\SondeoEnum;

use App\Enums\Calidad\EscuchaActivaEnum;
use App\Enums\Calidad\OfertaComercialEnum;
use App\Enums\Calidad\SolicitudNumeroAlternativoEnum;
use App\Enums\Calidad\AclaraDudasClienteEnum;
use App\Enums\Calidad\ManejoObjecionesEnum;
use App\Enums\Calidad\GenerarVentasIrregularesEnum;

use App\Enums\Calidad\AceptacionServicioEnum;
use App\Enums\Calidad\TecnicasDeCierreVentasEnum;
use App\Enums\Calidad\UtilizaTecnicasCierreEnum;
use App\Enums\Calidad\ValidacionVentaEnum;

use App\Enums\Calidad\DiccionEnum;
use App\Enums\Calidad\EsperaVaciosEnum;
use App\Enums\Calidad\EvitaMaltratoAlClienteEnum;
use App\Enums\Calidad\AbandonoLlamadaEnum;
use App\Enums\Calidad\LibertyNegativoEnum;




class EditCalidad extends EditRecord
{
    protected static string $resource = CalidadResource::class;


    // protected function mutateFormDataBeforeFill(array $data): array
    // {
    //     $data['agente'] = auth()->id();
    
    //     return $data;
    // }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Asign Logged in user to user_id in order to record who did the audit.
        // $data['last_edited_by_id'] = auth()->id();

        // Start with a total score of 100
        // If evaluacion_completo it's true, then the total score is 100, otherwise it's 0
        $totalScore = $data['evaluacion_completa'] ? 100 : 0;
        // $totalScore = 100;

        // Define the deduction values for each possible response selected for the Calidad audit
        // Tab Preventa
        $bienvenidaDeductions = BienvenidaEnum::toValues();
        $empatiaDeductions = EmpatiaEnum::toValues();
        $sondeoDeductions = SondeoEnum::toValues();

        // Tab Venta
        $escuchaActivaDeductions = EscuchaActivaEnum::toValues();
        $ofertaComercialDeductions = OfertaComercialEnum::toValues();
        $solicitudNumeroAlternativoDeductions = SolicitudNumeroAlternativoEnum::toValues();
        $aclaraDudasClienteDeductions = AclaraDudasClienteEnum::toValues();
        $manejoObjecionesDeductions = ManejoObjecionesEnum::toValues();
        $generarVentasIrregularesDeductions = GenerarVentasIrregularesEnum::toValues();

        // Tab Postventa
        $aceptacionServiciosDeductions = AceptacionServicioEnum::toValues();
        $tecnicasDeCierreVentasDeductions = TecnicasDeCierreVentasEnum::toValues();
        $utilizaTecnicasDeCierreDeductions = UtilizaTecnicasCierreEnum::toValues();
        $validacionVentaDeductions = ValidacionVentaEnum::toValues();
        
        // Tab evaluacion del agente
        $diccionDeductions = DiccionEnum::toValues();
        $empatiaEvaluacionDeductions = EmpatiaEnum::toValues();
        $escuchaVaciosDeductions = EsperaVaciosEnum::toValues();
        $escuchaActivaDeductions = EscuchaActivaEnum::toValues();
        $evitaMaltratoAlClienteDeductions = EvitaMaltratoAlClienteEnum::toValues();
        $abandonoLlamadaDeductions = AbandonoLlamadaEnum::toValues();
        $libertyNegativoDeductions = LibertyNegativoEnum::toValues();
        
        // Preventa
        // Assuming the checkbox responses are in the $data array
        $bienvenidaResponses = $data['bienvenida'];
        $empatiaResponses = $data['empatia'];

        // Deduct the values for the 'bienvenida' responses
        foreach ($bienvenidaResponses as $response) {
        // Deduct the value for this response from the total score
            if (isset($bienvenidaDeductions[$response])) {
                $totalScore -= $bienvenidaDeductions[$response];
                }
            }
        
            // Deduct the values for the 'empatia' responses
        foreach ($empatiaResponses as $response) {
            // Deduct the value for this response from the total score
            if (isset($empatiaDeductions[$response])) {
                $totalScore -= $empatiaDeductions[$response];
                }
            }

        // If the total score is less than 0, set it to 0
        if ($totalScore < 0) {
            $totalScore = 0;
        }
        // After mutating the data, return it
        $data['calificacion'] = $totalScore;
            
                return $data;
    }

    protected function beforfill(): void
    {
        // Runs after the form fields are populated from the database.
        // Populate 'agente' from database by quereing the current calidad id and returning user_id from calidad table
        $currentAgent = $this->record->user_id;
        $agente = User::where('id', $currentAgent)->get()->pluck('name', 'id');
        $data['agente'] = $agente;
        // Remove the return statement from the void function
        
        // will this set the value of the 'agente' field in the form to the value of the $agente variable?
        $this->record->agente = $agente;
        // $this->record->agente = $agente;
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
