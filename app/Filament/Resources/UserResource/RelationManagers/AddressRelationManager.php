<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\HasOneRelationManager;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;
use Filament\Actions;
use Log;

class AddressRelationManager extends RelationManager
{
    protected static string $relationship = 'address';
    protected static ?string $title = 'Endereço';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('cep')
                ->label('CEP')
                ->required()
                ->mask('99999-999')
                ->live(onBlur: true)
                ->dehydrateStateUsing(fn ($state) => 
                    $state ? preg_replace('/\D/','',$state) : null
                )
                ->afterStateUpdated(function ($state, callable $set) {
                    $cep = preg_replace('/\D/','',(string)$state);
                    if (strlen($cep) !== 8) return;

                    try {
                        $endereco = Http::timeout(5)->get("https://brasilapi.com.br/api/cep/v1/{$cep}");
                        if ($endereco->ok() && ! $endereco->json('erro')) {
                            Log::debug("CEP: {$cep} - Response: " . $endereco->body());
                            $endereco = $endereco->json();
                            $set('endereco', $endereco['street'] ?? null);
                            $set('bairro',   $endereco['neighborhood'] ?? null);
                            $set('cidade',   $endereco['city'] ?? null);
                            $set('estado',   $endereco['state'] ?? null);
                        }
                    } catch (\Throwable $e) {}
                }),
            Forms\Components\TextInput::make('endereco')
                ->label('Endereço')
                ->readOnly()
                ->required(),
            Forms\Components\TextInput::make('numero')
                ->label('Número')
                ->required()
                ->maxLength(20),
            Forms\Components\TextInput::make('complemento')
                ->label('Complemento')
                ->required()
                ->maxLength(120),
            Forms\Components\TextInput::make('bairro')
                ->label('Bairro')
                ->required()
                ->readOnly()
                ->maxLength(120),
            Forms\Components\TextInput::make('cidade')
                ->label('Cidade')
                ->required()
                ->readOnly()
                ->maxLength(120),
            Forms\Components\TextInput::make('estado')
                ->label('UF')
                ->required()
                ->readOnly()
                ->maxLength(2),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('cep')
                ->label('CEP')
                ->formatStateUsing(fn (?string $state) => $state
                    ? preg_replace('/(\d{5})(\d{3})/', '$1-$2', preg_replace('/\D/', '', $state))
                    : null
                ),
            Tables\Columns\TextColumn::make('endereco')
                ->label('Endereço')
                ->wrap(),
            Tables\Columns\TextColumn::make('numero')
                ->label('Número'),
            Tables\Columns\TextColumn::make('complemento')
                ->label('Complemento')
                ->wrap(),
            Tables\Columns\TextColumn::make('bairro')
                ->label('Bairro')
                ->wrap(),
            Tables\Columns\TextColumn::make('cidade')
                ->label('Cidade')
                ->wrap(),
            Tables\Columns\TextColumn::make('estado')
                ->label('UF'),
        ])
        ->headerActions([
            Tables\Actions\CreateAction::make()
                ->label('Adicionar endereço'),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ]);
    }
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Adicionar endereço'),

            Actions\EditAction::make()->label('Editar'),

            Actions\DeleteAction::make()
                ->label('Remover')
                ->requiresConfirmation(),
        ];
    }
}
