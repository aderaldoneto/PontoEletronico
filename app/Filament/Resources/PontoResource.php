<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PontoResource\Pages;
use App\Filament\Resources\PontoResource\RelationManagers;
use App\Models\Ponto;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PontoResource extends Resource
{
    protected static ?string $model = Ponto::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Horário'))
                    ->dateTime('d/m/Y H:i:s'),
            ])
            ->groups([
                Group::make('created_at')
                    ->label('Mês')
                    ->getKeyFromRecordUsing(fn (Ponto $r) => $r->created_at->format('Y-m'))
                    ->getTitleFromRecordUsing(fn (Ponto $r) => $r->created_at->translatedFormat('F/Y'))
                    ->collapsible(),
            ])
            ->defaultGroup('created_at')
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPontos::route('/'),
        ];
    }


    /**
     * @return Builder<Ponto>
     */
    public static function getEloquentQuery(): Builder
    {

        /** @var User $user */
        $user = auth()->user(); 

        return parent::getEloquentQuery()
            ->where('user_id', $user->id);

    }
}
