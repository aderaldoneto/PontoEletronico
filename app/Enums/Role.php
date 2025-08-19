<?php 

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case EMPLOYEE = 'employee';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN    => __('Administrador'),
            self::EMPLOYEE => __('Funcionário'),
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($c) => [$c->value => $c->label()])
            ->all();
    }
}
