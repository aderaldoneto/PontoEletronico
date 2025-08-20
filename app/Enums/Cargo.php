<?php 

namespace App\Enums;

enum Cargo: string
{
    case DIRETOR_EXECUTIVO = 'diretor_executivo';
    case DIRETOR_DE_OPERACOES = 'diretor_de_operacoes';
    case DIRETOR_FINANCEIRO = 'diretor_financeiro';
    case DIRETOR_DE_MARKETING = 'diretor_de_marketing';
    case DIRETOR_DE_TI = 'diretor_de_ti';
    case GERENTE_DE_DEPARTAMENTO = 'gerente_de_departamento';
    case COORDENADOR = 'coordenador';
    case SUPERVISOR = 'supervisor';
    case ANALISTA = 'analista';
    case ASSISTENTE = 'assistente';
    case AUXILIAR = 'auxiliar';
    case TECNICO = 'tecnico';
    case VENDEDOR = 'vendedor';
    case ATENDENTE = 'atendente';
    case RECEPCIONISTA = 'recepcionista';
    

    public function label(): string
    {
        return match ($this) {
            self::DIRETOR_EXECUTIVO             => __('Diretor Executivo'),
            self::DIRETOR_DE_OPERACOES          => __('Diretor de Operações'),
            self::DIRETOR_FINANCEIRO            => __('Diretor Financeiro'),
            self::DIRETOR_DE_MARKETING          => __('Diretor de Marketing'),
            self::DIRETOR_DE_TI                 => __('Diretor de TI'),
            self::GERENTE_DE_DEPARTAMENTO       => __('Gerente de Departamento'),
            self::COORDENADOR                   => __('Coordenador'),
            self::SUPERVISOR                    => __('Supervisor'),
            self::ANALISTA                      => __('Analista'),
            self::ASSISTENTE                    => __('Assistente'),
            self::AUXILIAR                      => __('Auxiliar'),
            self::TECNICO                       => __('Técnico'),
            self::VENDEDOR                      => __('Vendedor'),
            self::ATENDENTE                     => __('Atendente'),
            self::RECEPCIONISTA                 => __('Recepcionista'),
            
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($c) => [$c->value => $c->label()])
            ->all();
    }
}
