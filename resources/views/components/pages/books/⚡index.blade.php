<x-layouts.app>
    <div class="p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Gestão de Livros</h1>
                <p class="text-sm text-zinc-500">Consulte e gira o catálogo da biblioteca.</p>
            </div>
            
            <div class="w-full md:w-auto">
                <flux:input icon="magnifying-glass" placeholder="Pesquisar por ISBN ou Nome..." class="max-w-xs" />
            </div>
        </div>

        <flux:card class="overflow-hidden">
            <flux:table>
                <flux:columns>
                    <flux:column>Capa</flux:column>
                    <flux:column sortable>Nome</flux:column>
                    <flux:column>ISBN (Dados Cifrados)</flux:column>
                    <flux:column sortable>Preço</flux:column>
                    <flux:column align="end">Ações</flux:column>
                </flux:columns>

                <flux:rows>
                    <flux:row>
                        <flux:cell>
                            <div class="w-10 h-14 bg-zinc-200 dark:bg-zinc-700 rounded flex items-center justify-center text-[10px] text-center p-1">Sem Capa</div>
                        </flux:cell>
                        <flux:cell class="font-medium">O Senhor dos Anéis</flux:cell>
                        <flux:cell>
                            <code class="text-[10px] bg-zinc-100 dark:bg-zinc-800 px-2 py-1 rounded text-zinc-500">
                                eyJpdiI6Ik93S... (Cifrado)
                            </code>
                        </flux:cell>
                        <flux:cell>25.00€</flux:cell>
                        <flux:cell align="end">
                            <flux:button variant="ghost" size="sm" icon="pencil-square" />
                            <flux:button variant="ghost" size="sm" icon="trash" variant="danger" />
                        </flux:cell>
                    </flux:row>

                    <flux:row>
                        <flux:cell>
                            <div class="w-10 h-14 bg-zinc-200 dark:bg-zinc-700 rounded flex items-center justify-center text-[10px] text-center p-1">Sem Capa</div>
                        </flux:cell>
                        <flux:cell class="font-medium">Harry Potter e a Pedra Filosofal</flux:cell>
                        <flux:cell>
                            <code class="text-[10px] bg-zinc-100 dark:bg-zinc-800 px-2 py-1 rounded text-zinc-500">
                                eyJpdiI6IlFvT... (Cifrado)
                            </code>
                        </flux:cell>
                        <flux:cell>18.50€</flux:cell>
                        <flux:cell align="end">
                            <flux:button variant="ghost" size="sm" icon="pencil-square" />
                            <flux:button variant="ghost" size="sm" icon="trash" variant="danger" />
                        </flux:cell>
                    </flux:row>
                </flux:rows>
            </flux:table>
        </flux:card>

        <div class="mt-6 flex justify-end">
            <flux:button icon="document-arrow-down" variant="primary">
                Exportar para Excel
            </flux:button>
        </div>
    </div>
</x-layouts.app>