<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group heading="Plataforma">
                    <flux:sidebar.item icon="home" :href="route('dashboard')">Dashboard</flux:sidebar.item>
                    <flux:sidebar.item icon="book-open" :href="route('dashboard')">Livros</flux:sidebar.item>
                    <flux:sidebar.item icon="shopping-cart" :href="route('admin.orders.index')">Encomendas Admin</flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            @auth
                <div class="p-4 text-zinc-500 text-sm">
                    {{ auth()->user()->name }}
                </div>
                <form method="POST" action="{{ route('logout') }}" class="p-4">
                    @csrf
                    <button type="submit" class="text-xs text-red-500 uppercase font-bold">Sair</button>
                </form>
            @endauth
        </flux:sidebar>

        <main class="p-8">
            {{ $slot }}
        </main>

        @fluxScripts
    </body>
</html>