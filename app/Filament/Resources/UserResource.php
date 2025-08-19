<?php

namespace App\Filament\Resources;

use App\Enums\Role;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    // protected static ?string $navigationGroup = 'Configuração';

    public static function canAccess(): bool
    {
        /** @var User $user */
        $user = auth()->user();

        // return $user->role === Role::ADMIN;
        return true; 
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('cpf')
                    ->label('CPF')
                    ->required()
                    ->placeholder('000.000.000-00')
                    ->mask('999.999.999-99')
                    ->dehydrateStateUsing(fn ($state) => preg_replace('/\D/', '', $state)) 
                    ->unique(ignoreRecord: true)
                    ->maxLength(14),
                Forms\Components\DatePicker::make('data_nascimento')
                    ->label('Data de Nascimento')
                    ->required()
                    ->maxDate(Carbon::now()->subYears(0)),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('role')
                    ->label('Cargo')
                    ->options(Role::options())
                    ->native(false)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        /** @var User $user */
        $user = auth()->user();

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cpf')
                    ->label('CPF')
                    ->formatStateUsing(fn (?string $state) => $state
                        ? preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', preg_replace('/\D/', '', $state))
                        : null
                    )
                    ->searchable(),
                Tables\Columns\TextColumn::make('data_nascimento')
                    ->label('Idade') 
                    ->formatStateUsing(fn (?string $state) => $state
                        ? Carbon::parse($state)->age . ' anos'
                        : null
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cargo')
                    ->label('Cargo'),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Criado por')
                    ->placeholder('—')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => $record->id !== $user->id),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => $record->id !== $user->id),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
