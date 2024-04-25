<?php

namespace App\Filament\Resources\CalidadResource\Pages;

use App\Filament\Resources\CalidadResource;
use App\Models\Calidad;
use Filament\Resources\Pages\CreateRecord;
use Carbon\Carbon;

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


class CreateCalidad extends CreateRecord
{
    protected static string $resource = CalidadResource::class;

    protected static ?string $title = 'Auditoria Calidad';

    protected ?string $heading = 'Auditoria Calidad';

    // mutateFormDataBeforeCreate for user_id and call duration by subtracting the start and end time of the call using carbon

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        

        // If evaluacion_completo it's true, then the total score is 100, otherwise it's 0
        $totalScore = $data['evaluacion_completa'] ? 100 : 0;

        // Define the deduction values for each possible response selected for the Calidad audit
        $bienvenidaDeductions = BienvenidaEnum::toValues();

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
        $sondeoResponses = $data['sondeo'];
        
        // Venta
        $escuchaActivaResponses = $data['escucha_activa'];
        $ofertaComercialResponses = $data['oferta_comercial'];
        $solicitudNumeroAlternativoResponses = $data['numero_alternativo'];
        $aclaraDudasClienteDeductions = $data['aclara_dudas_cliente'];
        $manejoObjecionesResponses = $data['manejo_objeciones'];
        $generarVentasIrregularesResponses = $data['genera_ventas_irregulares'];

        // Postventa
        $aceptacionServiciosResponses = $data['aceptacion_servicio'];
        $tecnicasDeCierreVentasResponses = $data['tecnicas_cierre'];
        $utilizaTecnicasDeCierreResponses = $data['utiliza_tecnicas_cierre'];
        $validacionVentaResponses = $data['validacion_venta'];

        // Evaluacion del agente
        $diccionResponses = $data['diccion'];
        $empatiaEvaluacionResponses = $data['empatia_evalucion_agente'];
        $escuchaVaciosResponses = $data['espera_vacios'];
        $escuchaActivaResponses = $data['escucha_activa_evaluacion_agente'];
        $evitaMaltratoAlClienteResponses = $data['evita_maltrato'];
        $abandonoLlamadaResponses = $data['abandono_llamada'];
        $libertyNegativoResponses = $data['liberty_negativo'];

        // Preventa
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
        
        // Deduct the values for the 'sondeo' responses
        foreach ($sondeoResponses as $response) {
            // Deduct the value for this response from the total score
            if (isset($sondeoDeductions[$response])) {
                $totalScore -= $sondeoDeductions[$response];
                }
            }

        // Venta
        // Deduct the values for the 'escucha_activa' responses
        foreach ($escuchaActivaResponses as $response) {
            // Deduct the value for this response from the total score
            if (isset($escuchaActivaDeductions[$response])) {
                $totalScore -= $escuchaActivaDeductions[$response];
                }
            }
        
        // Deduct the values for the 'oferta_comercial' responses
        foreach ($ofertaComercialResponses as $response) {
            // Deduct the value for this response from the total score
            if (isset($ofertaComercialDeductions[$response])) {
                $totalScore -= $ofertaComercialDeductions[$response];
                }
            }

        // Deduct the values for the 'solicitud_numero_alternativo' responses
        foreach ($solicitudNumeroAlternativoResponses as $response) {
            // Deduct the value for this response from the total score
            if (isset($solicitudNumeroAlternativoDeductions[$response])) {
                $totalScore -= $solicitudNumeroAlternativoDeductions[$response];
                }
            }
        
        // Deduct the values for the 'aclara_dudas_cliente' responses
        foreach ($aclaraDudasClienteDeductions as $response) {
            // Deduct the value for this response from the total score
            if (isset($aclaraDudasClienteDeductions[$response])) {
                $totalScore -= $aclaraDudasClienteDeductions[$response];
                }
            }

        // Deduct the values for the 'manejo_objeciones' responses
        foreach ($manejoObjecionesResponses as $response) {
            // Deduct the value for this response from the total score
            if (isset($manejoObjecionesDeductions[$response])) {
                $totalScore -= $manejoObjecionesDeductions[$response];
                }
            }
        
        // Deduct the values for the 'generar_ventas_irregulares' responses
        foreach ($generarVentasIrregularesResponses as $response) {
            // Deduct the value for this response from the total score
            if (isset($generarVentasIrregularesDeductions[$response])) {
                $totalScore -= $generarVentasIrregularesDeductions[$response];
                }
            }
        
        // Postventa
        // Deduct the values for the 'aceptacion_servicio' responses
        foreach ($aceptacionServiciosResponses as $response) {
            // Deduct the value for this response from the total score
            if (isset($aceptacionServiciosDeductions[$response])) {
                $totalScore -= $aceptacionServiciosDeductions[$response];
                }
            }
        
        // Deduct the values for the 'tecnicas_cierre' responses
        foreach ($tecnicasDeCierreVentasResponses as $response) {
            // Deduct the value for this response from the total score
            if (isset($tecnicasDeCierreVentasDeductions[$response])) {
                $totalScore -= $tecnicasDeCierreVentasDeductions[$response];
                }
            }

        // Deduct the values for the 'utiliza_tecnicas_cierre' responses
        foreach ($utilizaTecnicasDeCierreResponses as $response) {
            // Deduct the value for this response from the total score
            if (isset($utilizaTecnicasDeCierreDeductions[$response])) {
                $totalScore -= $utilizaTecnicasDeCierreDeductions[$response];
                }
            }

        // Deduct the values for the 'validacion_venta' responses
        foreach ($validacionVentaResponses as $response) {
            // Deduct the value for this response from the total score
            if (isset($validacionVentaDeductions[$response])) {
                $totalScore -= $validacionVentaDeductions[$response];
                }
            }
        
        // Evaluacion del agente
        // Deduct the values for the 'diccion' responses
        foreach ($diccionResponses as $response) {
            // Deduct the value for this response from the total score
            if (isset($diccionDeductions[$response])) {
                $totalScore -= $diccionDeductions[$response];
                }
            }
        
        // Deduct the values for the 'empatia_evalucion_agente' responses
        foreach ($empatiaEvaluacionResponses as $response) {
            // Deduct the value for this response from the total score
            if (isset($empatiaEvaluacionDeductions[$response])) {
                $totalScore -= $empatiaEvaluacionDeductions[$response];
                }
            }
        
        // Deduct the values for the 'escucha_vacios' responses
        foreach ($escuchaVaciosResponses as $response) {
            // Deduct the value for this response from the total score
            if (isset($escuchaVaciosDeductions[$response])) {
                $totalScore -= $escuchaVaciosDeductions[$response];
                }
            }

        // Deduct the values for the 'escucha_activa_evaluacion_agente' responses
        foreach ($escuchaActivaResponses as $response) {
            // Deduct the value for this response from the total score
            if (isset($escuchaActivaDeductions[$response])) {
                $totalScore -= $escuchaActivaDeductions[$response];
                }
            }

        // Deduct the values for the 'evita_maltrato' responses
        foreach ($evitaMaltratoAlClienteResponses as $response) {
            // Deduct the value for this response from the total score
            if (isset($evitaMaltratoAlClienteDeductions[$response])) {
                $totalScore -= $evitaMaltratoAlClienteDeductions[$response];
                }
            }
        
        // Deduct the values for the 'abandono_llamada' responses
        foreach ($abandonoLlamadaResponses as $response) {
            // Deduct the value for this response from the total score
            if (isset($abandonoLlamadaDeductions[$response])) {
                $totalScore -= $abandonoLlamadaDeductions[$response];
                }
            }

        // Deduct the values for the 'liberty_negativo' responses
        foreach ($libertyNegativoResponses as $response) {
            // Deduct the value for this response from the total score
            if (isset($libertyNegativoDeductions[$response])) {
                $totalScore -= $libertyNegativoDeductions[$response];
                }
            }

        // If the total score is less than 0, set it to 0
        if ($totalScore < 0) {
                $totalScore = 0;
            }

        // Add the total score to the data array
        $data['calificacion'] = $totalScore;
        // Asign Logged in user to user_id in order to record who did the audit.
        $data['user_id'] = auth()->id();
        // dd($data);    
        // After mutating the data, return it
        return $data;

    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    
}
