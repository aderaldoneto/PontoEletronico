<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Support\Facades\DB;

class RelatorioPontos extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Relatório de Pontos';
    protected static string $view = 'filament.pages.relatorio-pontos';

    protected ?string $maxContentWidth = 'full';

    public ?string $from = null;
    public ?string $to   = null;

    public array $rows = [];
    public int $total = 0;

    public static function getLabel(): string
    {
        return __('Relatório de Pontos');
    }

    public static function getNavigationGroup(): string
    {
        return __('Relatórios');
    }

    public static function canAccess(): bool
    {
        /** @var User $user */
        $user = auth()->user();

        // return $user->role === Role::ADMIN;
        return true;
    }
    

    public function mount(): void
    {
        $this->loadRows();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\DatePicker::make('from')->label('De'),
            Forms\Components\DatePicker::make('to')->label('Até'),
            Forms\Components\Actions::make([
                Forms\Components\Actions\Action::make('filtrar')
                    ->label('Filtrar')
                    ->submit('loadRows'),
            ]),
        ])->columns(3)->statePath('data');
    }

    public function loadRows(): void
    {
        $from = $this->from;
        $to   = $this->to;

        $where = '1=1';
        $bindings = [];

        if ($from) { 
            $where .= ' AND p.created_at >= ?'; $bindings[] = $from.' 00:00:00'; 
        }
        
        if ($to) { 
            $where .= ' AND p.created_at <= ?'; $bindings[] = $to.' 23:59:59'; 
        }

        $countSql = "
            SELECT COUNT(*) AS total
            FROM pontos p
            INNER JOIN users u ON u.id = p.user_id
            LEFT JOIN users m  ON m.id = u.created_by
            WHERE {$where}";

        $this->total = (int) (DB::selectOne($countSql, $bindings)->total ?? 0);

        $sql = "
            SELECT
              p.id AS registro_id,
              u.name AS funcionario_nome,
              u.role AS cargo,
              TIMESTAMPDIFF(YEAR, u.data_nascimento, CURDATE()) AS idade,
              m.name AS gestor_nome,
              DATE_FORMAT(p.created_at, '%Y-%m-%d %H:%i:%s') AS data_hora
            FROM pontos p
            INNER JOIN users u ON u.id = p.user_id
            LEFT JOIN users m  ON m.id = u.created_by
            WHERE {$where}
            ORDER BY p.created_at DESC
            LIMIT 100";
            
        $this->rows = DB::select($sql, $bindings);
    }
}
