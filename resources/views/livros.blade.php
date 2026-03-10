<x-layouts.app>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold">Gestão de Livros</h1>
                <p class="text-sm text-zinc-500">Listagem de títulos da biblioteca.</p>
            </div>
            <flux:input icon="magnifying-glass" placeholder="Pesquisar livros..." class="max-w-xs" />
        </div>

        <flux:card>
            <flux:table>
                <flux:columns>
                    <flux:column>Capa</flux:column>
                    <flux:column sortable>Nome</flux:column>
                    <flux:column>ISBN (Cifrado)</flux:column>
                    <flux:column>Preço</flux:column>
                </flux:columns>

                <flux:rows>
                    <flux:row>
                        <flux:cell><div class="w-10 h-14 bg-zinc-200 dark:bg-zinc-700 rounded animate-pulse"></div></flux:cell>
                        <flux:cell class="font-medium">O Senhor dos Anéis</flux:cell>
                        <flux:cell><code class="text-xs text-zinc-500 px-2 py-1 bg-zinc-100 dark:bg-zinc-800 rounded">eyJpdiI6Ik93S...</code></flux:cell>
                        <flux:cell>25.00€</flux:cell>
                    </flux:row>
                    <flux:row>
                        <flux:cell><div class="w-10 h-14 bg-zinc-200 dark:bg-zinc-700 rounded animate-pulse"></div></flux:cell>
                        <flux:cell class="font-medium">Harry Potter</flux:cell>
                        <flux:cell><code class="text-xs text-zinc-500 px-2 py-1 bg-zinc-100 dark:bg-zinc-800 rounded">eyJpdiI6IlFvT...</code></flux:cell>
                        <flux:cell>18.50€</flux:cell>
                    </flux:row>
                </flux:rows>
            </flux:table>
        </flux:card>

        <div class="mt-6 flex justify-end">
            <flux:button icon="document-arrow-down" variant="primary">Exportar para Excel</flux:button>
        </div>
    </div>
</x-layouts.app>