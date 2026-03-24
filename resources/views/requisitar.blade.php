<!DOCTYPE html>
<html lang="pt" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Solicitar Requisição - Inovcorp</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-base-200 min-h-screen flex items-center justify-center p-4">
    <div class="card w-full max-w-2xl bg-base-100 shadow-2xl">
        <div class="card-body">
            <h2 class="card-title text-3xl text-primary font-bold">Solicitar Requisição</h2>
            <p class="text-sm opacity-60 mb-6">Confirme os detalhes do livro e anexe a sua foto para prosseguir.</p>

            <form action="{{ route('requisicao.enviar', $id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="bg-base-200 p-6 rounded-xl flex gap-6 mb-6 items-center">
                    <div class="w-24 h-32 bg-gray-300 rounded shadow-inner flex items-center justify-center font-bold text-gray-500">CAPA</div>
                    <div>
                        <h3 class="text-xl font-bold">Título do Livro (ID: {{ $id }})</h3>
                        <p class="text-sm opacity-70">ISBN: 123-456-789</p>
                        <div class="badge badge-outline mt-2 italic">Devolução em 5 dias</div>
                    </div>
                </div>

                <div class="form-control w-full mb-6">
                    <label class="label font-bold">Sua Foto (Obrigatório)</label>
                    <input type="file" name="foto" class="file-input file-input-bordered file-input-primary w-full" required />
                    <label class="label">
                        <span class="label-text-alt text-error italic">Requisito de estágio: Identificação visual necessária.</span>
                    </label>
                </div>

                <div class="card-actions justify-end gap-2">
                    <a href="{{ route('dashboard') }}" class="btn btn-ghost">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-8">Confirmar Empréstimo</button>
                </div>
            </form>

        </div>
    </div>
</body>
</html>