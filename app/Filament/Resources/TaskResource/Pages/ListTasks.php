<?php

namespace App\Filament\Resources\TaskResource\Pages;

use Filament\Actions;
use App\Filament\Resources\TaskResource;
use Filament\Resources\Pages\ListRecords;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nova Tarefa')
                ->modalSubmitActionLabel('Salvar Tarefa')
                ->modalHeading()
                ->closeModalByClickingAway(false),
        ];
    }
}
