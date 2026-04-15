@component('layouts.app')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex justify-between items-center mb-6 border-b pb-4">
                    <h2 class="text-2xl font-bold text-gray-800">Histórico de Auditoria</h2>
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded uppercase">Fase 6</span>
                </div>

                <div class="overflow-x-auto border rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3">Data/Hora</th>
                                <th class="px-6 py-3">Utilizador</th>
                                <th class="px-6 py-3">Módulo</th>
                                <th class="px-6 py-3">Descrição</th>
                                <th class="px-6 py-3">IP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($logs as $log)
                                <tr class="bg-white hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium">
                                        {{ $log->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 mr-2">
                                                {{ substr($log->user->name ?? 'S', 0, 1) }}
                                            </div>
                                            {{ $log->user->name ?? 'Sistema' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-md text-xs font-bold uppercase {{ $log->modulo == 'Autenticação' ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700' }}">
                                            {{ $log->modulo }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs truncate text-gray-600" title="{{ $log->alteracao }}">
                                        {{ $log->alteracao }}
                                    </td>
                                    <td class="px-6 py-4 text-xs font-mono text-gray-400">
                                        {{ $log->ip }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">
                                        Ainda não existem movimentos registados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
@endcomponent