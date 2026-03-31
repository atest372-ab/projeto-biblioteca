<!DOCTYPE html>
<html lang="pt" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Moderação de Reviews - INOVCORP</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-base-200 min-h-screen">
    <div class="navbar bg-primary text-primary-content shadow-lg px-8">
        <a href="{{ route('dashboard') }}" class="btn btn-ghost text-xl font-black italic">INOVCORP LIB</a>
    </div>

    <div class="p-8 max-w-5xl mx-auto">
        <h2 class="text-3xl font-bold mb-6">Moderação de Avaliações</h2>

        @if(session('success'))
            <div class="alert alert-success mb-4 text-white font-bold shadow-lg">
                <div>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <div class="card bg-base-100 shadow-xl overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Utilizador</th>
                        <th>Livro</th>
                        <th>Comentário</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td>{{ $review->user->name }}</td>
                            <td>{{ $review->book->name }}</td>
                            <td class="italic text-sm">"{{ $review->comment }}"</td>
                            <td><span class="badge badge-warning uppercase text-xs font-bold">{{ $review->status }}</span></td>
                            <td class="flex gap-2">
                                <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="ativo">
                                    <button type="submit" class="btn btn-success btn-xs text-white">Aprovar</button>
                                </form>

                                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Tem a certeza que deseja eliminar esta avaliação permanentemente?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-error btn-xs text-white">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-8 opacity-50 italic">Nenhuma avaliação pendente de moderação.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>