<!DOCTYPE html>
<html lang="pt" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Requisitar Livro - Inovcorp</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-base-200 min-h-screen p-4 md:p-10">
    <div class="max-w-2xl mx-auto card bg-base-100 shadow-2xl">
        <div class="card-body">
            <h2 class="card-title text-2xl text-primary">Solicitar Requisição</h2>
            <p class="text-sm text-base-content/60">Confirme os detalhes do livro e anexe a sua foto para prosseguir.</p>

            <div class="divider"></div>

            <div class="flex gap-4 bg-base-200 p-4 rounded-lg mb-6">
                <div class="w-24 h-32 bg-gray-300 rounded flex items-center justify-center font-bold">CAPA</div>
                <div>
                    <h3 class="text-lg font-bold">Título do Livro</h3>
                    <p class="text-sm">ISBN: <span class="font-mono">123-456-789</span></p>
                    <div class="badge badge-outline mt-2 italic">Devolução em 5 dias</div>
                </div>
            </div>

            <form action="#" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-control w-full mb-6">
                    <label class="label">
                        <span class="label-text font-bold">Sua Foto (Obrigatório)</span>
                    </label>
                    <input type="file" name="foto_cidadao" class="file-input file-input-bordered file-input-primary w-full" required />
                    <label class="label">
                        <span class="label-text-alt text-error italic">Requisito de estágio: Identificação visual necessária.</span>
                    </label>
                </div>

                <div class="card-actions justify-end">
                    <a href="{{ route('dashboard') }}" class="btn btn-ghost">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Confirmar Empréstimo</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>