<?php

namespace App\Filament\Resources\PontoResource\Pages;

use App\Filament\Resources\PontoResource;
use App\Models\Ponto;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListPontos extends ListRecords
{
    protected static string $resource = PontoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('registrarPonto')
                ->label('Registrar ponto')
                ->icon('heroicon-o-clock')
                ->color('primary')
                
                ->action(function () {
                    Ponto::create([
                        'user_id' => auth()->id(),
                    ]);

                    Notification::make()
                        ->title('Ponto registrado!')
                        ->success()
                        ->send();
                })

                ->after(fn () => $this->redirect(PontoResource::getUrl())),
        ];
    }
}
