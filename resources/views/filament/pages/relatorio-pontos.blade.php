<x-filament::page>
    <div class="flex flex-col gap-4 min-h-[calc(100vh-8rem)]">
        <div class="sticky top-0 z-10 bg-white/70 backdrop-blur p-2 rounded border">
            {{ $this->form }}
        </div>

        <div class="flex-1 overflow-auto rounded border">
            <table class="w-full table-fixed">
                <thead class="bg-gray-50 sticky top-0">
                    <tr>
                        <th class="p-2 text-left">ID</th>
                        <th class="p-2 text-left">Funcionário</th>
                        <th class="p-2 text-left">Cargo</th>
                        <th class="p-2 text-left">Idade</th>
                        <th class="p-2 text-left">Gestor</th>
                        <th class="p-2 text-left">Data/Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->rows as $row)
                        <tr class="border-t">
                            <td class="p-2">{{ $row->registro_id }}</td>
                            <td class="p-2">{{ $row->funcionario_nome }}</td>
                            <td class="p-2">{{ $row->cargo }}</td>
                            <td class="p-2">{{ $row->idade }}</td>
                            <td class="p-2">{{ $row->gestor_nome }}</td>
                            <td class="p-2">{{ $row->data_hora }}</td>
                        </tr>
                    @empty
                        <tr><td class="p-4" colspan="6">Sem resultados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="text-sm text-gray-600">Total: {{ $this->total }}</div>
    </div>
</x-filament::page>
