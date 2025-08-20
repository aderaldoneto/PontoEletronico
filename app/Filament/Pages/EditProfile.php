<?php

namespace App\Filament\Pages;

use Carbon\Carbon;
use Exception;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EditProfile extends \Filament\Pages\Auth\EditProfile
{
     public static function getLabel(): string
    {
        return __('Editar perfil');
    }

    /**
     * @return array<string, Form>
     *
     * @throws Exception
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->makeForm()
                ->columns(12)
                ->schema([
                    Section::make()->columnSpan(8)->columns()->schema([
                        TextInput::make('name')
                            ->label(__('Nome'))
                            ->required()
                            ->maxLength(255)
                            ->autofocus()
                            ->columnSpan(1),
                        TextInput::make('cpf')
                            ->label(__('CPF'))
                            ->readOnly()
                            ->mask('999.999.999-99')
                            ->columnSpan(1),
                        DatePicker::make('data_nascimento')
                            ->label('Data de Nascimento')
                            ->required()
                            ->maxDate(Carbon::now()->subYears(0)),
                        TextInput::make('email')
                            ->label(__('E-mail'))
                            ->email()
                            ->readOnly()
                            ->columnSpan(1),
                        Group::make([
                            TextInput::make('password')
                                ->label(__('Nova senha'))
                                ->password()
                                ->revealable()
                                ->autocomplete('new-password')
                                ->dehydrated(fn ($state): bool => filled($state))
                                ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
                                ->live(debounce: 300)
                                ->same('password_confirmation'),
                            TextInput::make('password_confirmation')
                                ->label(__('Confirmar nova senha'))
                                ->password()
                                ->revealable()
                                ->required()
                                ->visible(fn (Get $get): bool => filled($get('password')))
                                ->dehydrated(false),
                        ])->columnSpan(2)->columns(),
                        TextInput::make('current_password')
                            ->label(__('Senha atual'))
                            ->password()
                            ->revealable()
                            ->currentPassword()
                            ->required()
                            ->visible(function (Get $get): bool {
                                return filled($get('password'))
                                    || $get('email') !== auth()->user()?->email;
                            })
                            ->dehydrated(false),
                    ]),
                ])
                ->operation('edit')
                ->model($this->getUser())
                ->statePath('data'),
        ];
    }
}
