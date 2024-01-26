<?php

namespace App\Filament\Resources\CalidadResource\Pages;

use App\Filament\Resources\CalidadResource;
use App\Models\Calidad;
use Filament\Resources\Pages\CreateRecord;
use Carbon\Carbon;

class CreateCalidad extends CreateRecord
{
    protected static string $resource = CalidadResource::class;

    protected static ?string $title = 'Auditoria Calidad';

    protected ?string $heading = 'Auditoria Calidad';

    // mutateFormDataBeforeCreate for user_id and call duration by subtracting the start and end time of the call using carbon

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Need to mutate data on related table
        $data['user_id'] = auth()->id();
        // Lets get loop over the array of data from the repeater "grabaciones" and get the duration of each call and add it to the total duration in seconds
        // $totalDuration = 0;
        // $grabaciones = request()->input('grabaciones');
        
        // // Create a new Calidad instance record and save it to the databese.
        // $calidad = Calidad::create($data); 

        // foreach ($data['grabaciones'] as $grabacion) {
        //     $start = Carbon::parse($grabacion['dia_hora_inicio']);
        //     $end = Carbon::parse($grabacion['dia_hora_final']);
        //     $duration = $start->diffInSeconds($end);
        //     $totalDuration += $duration;

        // $grabacion['duracion'] = $duration;
        // // Create the related model
        // $calidad->grabacionauditoria()->create($grabacion);
            
        // // Create a new CalidadAuditoria instance record for each call and save it to the databese.
        // $calidadAuditoria = new \App\Models\CalidadAuditoria([
        //     'dia_hora_inicio' => $grabacion['dia_hora_inicio'],
        //     'dia_hora_final' => $grabacion['dia_hora_final'],
        //     'duracion' => $grabacion['duracion'],

        // ]);
        
        // $calidad->grabacionauditoria()->save($calidadAuditoria);

        
        // }

        
        
        // Now we convert the total duration in seconds to hours, minutes and seconds
        // $hours = floor($totalDuration / 3600);
        // $minutes = floor(($totalDuration - ($hours * 3600)) / 60);
        // $seconds = $totalDuration - ($hours * 3600) - ($minutes * 60);
        // $data['duracion'] = $hours . ':' . $minutes . ':' . $seconds;
        // $data['hours'] = $hours;
        // $data['minutes'] = $minutes;
        // $data['seconds'] = $seconds;


        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
