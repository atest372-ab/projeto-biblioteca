<!DOCTYPE html>
<html lang="pt" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Inovcorp - Pesquisar Google Books</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-base-200 min-h-screen">

    <div class="navbar bg-primary text-primary-content shadow-lg px-8">
        <div class="flex-1">
            <a class="btn btn-ghost text-xl font-black italic tracking-tighter text-white">INOVCORP LIB</a>
        </div>
        <div class="flex-none">
            <ul class="menu menu-horizontal px-1 font-semibold">
                <li><a href="{{ route('dashboard') }}" class="text-white">Dashboard</a></li>
                <li><a href="{{ route('google.search') }}" class="bg-white text-primary rounded-lg">Fase 3: Google API</a></li>
            </ul>
        </div>
    </div>

    <div class="p-8 max-w-7xl mx-auto">
        <div class="card bg-base-100 shadow-xl mb-8">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-4 text-gray-700 font-bold">Importar da Google Books API</h2>
                
                <form action="{{ route('google.search') }}" method="GET" class="join w-full">
                    <input name="search" class="input input-bordered join-item w-full" placeholder="Ex: Clean Code, Laravel, Harry Potter..." value="{{ request('search') }}" />
                    <button type="submit" class="btn btn-primary join-item text-white">Pesquisar</button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($books as $book)
                @php 
                    $info = $book['volumeInfo'] ?? [];
                    $title = $info['title'] ?? 'Título Indisponível';
                    
                    // Correção de Imagem: Forçar HTTPS para evitar bloqueios do browser
                    $thumb = $info['imageLinks']['thumbnail'] ?? 'https://via.placeholder.com/150x200?text=Sem+Capa';
                    $thumbnail = str_replace('http://', 'https://', $thumb);
                    
                    // Correção de ISBN: Procurar no array de identificadores
                    $isbn = 'N/A';
                    if (isset($info['industryIdentifiers'])) {
                        foreach ($info['industryIdentifiers'] as $id) {
                            if ($id['type'] == 'ISBN_13' || $id['type'] == 'ISBN_10') {
                                $isbn = $id['identifier'];
                                break;
                            }
                        }
                    }
                @endphp
                
                <div class="card card-side bg-base-100 shadow-md hover:shadow-2xl transition-all border border-gray-100">
                    <figure class="w-1/3 bg-gray-200">
                        <img src="{{ $thumbnail }}" alt="Capa" class="h-full w-full object-cover" />
                    </figure>
                    
                    <div class="card-body w-2/3 p-4">
                        <h3 class="font-bold text-sm h-12 overflow-hidden leading-tight text-gray-800">{{ $title }}</h3>
                        <p class="text-xs text-gray-500 mb-2 italic">ISBN: {{ $isbn }}</p>
                        
                        <div class="card-actions justify-end mt-auto">
                            <form action="{{ route('google.import') }}" method="POST">
                                @csrf
                                <input type="hidden" name="name" value="{{ $title }}">
                                <input type="hidden" name="isbn" value="{{ $isbn }}">
                                <input type="hidden" name="cover_image" value="{{ $thumbnail }}">
                                <input type="hidden" name="bibliography" value="{{ $info['description'] ?? 'Descrição importada via Google API.' }}">
                                
                                <button type="submit" class="btn btn-success btn-xs text-white">Importar para BD</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                @if(request('search'))
                    <div class="col-span-full alert alert-warning shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <span>A API não retornou resultados. Verifica a tua ligação ou tenta um termo mais genérico.</span>
                    </div>
                @endif
            @endforelse
        </div>
    </div>
</body>
</html>