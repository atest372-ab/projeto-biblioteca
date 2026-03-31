<!DOCTYPE html>
<html lang="pt" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>{{ $book->title ?? $book->name }} - INOVCORP</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-base-200 min-h-screen pb-20">
    <div class="navbar bg-primary text-primary-content shadow-lg px-8">
        <a href="{{ route('dashboard') }}" class="btn btn-ghost text-xl font-black italic">INOVCORP LIB</a>
    </div>

    <div class="p-8 max-w-5xl mx-auto">
        {{-- CARD PRINCIPAL DO LIVRO --}}
        <div class="card lg:card-side bg-base-100 shadow-xl overflow-hidden mb-10">
            <figure class="lg:w-1/3 bg-gray-200">
                <img src="{{ $book->cover_image ?? 'https://via.placeholder.com/300x450' }}" alt="Capa" class="w-full h-full object-cover shadow-2xl" />
            </figure>
            <div class="card-body lg:w-2/3">
                <h2 class="card-title text-4xl font-bold mb-2 text-gray-800">{{ $book->title ?? $book->name }}</h2>
                <div class="badge badge-outline mb-4">ISBN: {{ $book->isbn }}</div>
                <p class="text-gray-600 leading-relaxed text-justify mb-6">
                    {{ $book->bibliography }}
                </p>
                <div class="card-actions justify-end mt-auto">
                    <div class="stat-value text-primary text-2xl mr-4">{{ $book->price }}€</div>
                    <a href="{{ route('livro.requisitar', $book->id) }}" class="btn btn-primary">Requisitar Agora</a>
                </div>
            </div>
        </div>

        <hr class="my-10 border-base-300">

        {{-- 1. SECÇÃO DE ENVIAR AVALIAÇÃO --}}
        <div class="mb-10">
            @if(Auth::user()->role === 'cidadao')
                <h3 class="text-2xl font-bold mb-6">Deixe a sua avaliação</h3>
                <form action="{{ route('reviews.store', $book->id) }}" method="POST" class="bg-base-100 p-8 rounded-2xl shadow-sm border border-base-300">
                    @csrf
                    <div class="form-control mb-4">
                        <label class="label"><span class="label-text font-semibold">O seu comentário</span></label>
                        <textarea name="comment" class="textarea textarea-bordered h-24" placeholder="O que achou deste livro?" required></textarea>
                    </div>

                    <div class="flex flex-col md:flex-row gap-6 mb-6">
                        <div class="form-control w-full max-w-xs">
                            <label class="label"><span class="label-text font-semibold">Nota</span></label>
                            <select name="rating" class="select select-bordered" required>
                                <option value="5">5 ⭐⭐⭐⭐⭐ (Excelente)</option>
                                <option value="4">4 ⭐⭐⭐⭐ (Muito Bom)</option>
                                <option value="3">3 ⭐⭐⭐ (Bom)</option>
                                <option value="2">2 ⭐⭐ (Razoável)</option>
                                <option value="1">1 ⭐ (Fraco)</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary px-8">Submeter Avaliação</button>
                </form>
            @else
                <div class="alert alert-info shadow-sm bg-blue-50 border-blue-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Como Administrador, pode gerir as avaliações na área de moderação.</span>
                </div>
            @endif
        </div>

        {{-- 2. LISTA DE AVALIAÇÕES APROVADAS --}}
        <div class="mb-16">
            <h3 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <span class="text-yellow-500 font-bold">★</span> Avaliações dos Leitores
            </h3>
            <div class="space-y-4">
                @forelse($reviews as $review)
                    <div class="bg-base-100 p-6 rounded-xl shadow-sm border border-base-300">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <span class="font-bold text-lg text-primary block">{{ $review->user->name }}</span>
                                <span class="text-xs opacity-50">{{ $review->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="text-yellow-500 font-bold text-lg">
                                {{ str_repeat('⭐', $review->rating) }}
                            </div>
                        </div>
                        <p class="text-gray-700 italic leading-relaxed">"{{ $review->comment }}"</p>
                    </div>
                @empty
                    <div class="bg-base-100 p-10 rounded-xl text-center border-2 border-dashed border-base-300">
                        <p class="opacity-50 italic">Ainda não existem avaliações aprovadas para este livro.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- 3. LIVROS RELACIONADOS --}}
        <div class="bg-base-300 p-8 rounded-3xl">
            <h3 class="text-xl font-bold mb-6">Também poderá gostar de...</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $relacionados = \App\Models\Book::where('id', '!=', $book->id)->take(3)->get();
                @endphp
                @forelse($relacionados as $item)
                    <div class="card bg-base-100 shadow-sm hover:shadow-xl transition-all duration-300">
                        <div class="card-body p-4">
                            <h4 class="font-bold text-primary line-clamp-1">{{ $item->title ?? $item->name }}</h4>
                            <p class="text-xs opacity-60 mb-4">ISBN: {{ $item->isbn }}</p>
                            <a href="{{ route('livros.show', $item->id) }}" class="btn btn-xs btn-outline btn-primary">Ver Livro</a>
                        </div>
                    </div>
                @empty
                    <p class="text-sm opacity-50 italic col-span-3 text-center">Sem outras sugestões de momento.</p>
                @endforelse
            </div>
        </div>
    </div>
</body>
</html>