<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Biblioteca</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-4xl mx-auto bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Gestão da Biblioteca</h1>
        <p class="mb-6 text-gray-600">Dados protegidos com encriptação AES-256.</p>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 uppercase text-xs">
                    <th class="p-3 border-b">Título</th>
                    <th class="p-3 border-b">ISBN (Cifrado)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="p-3 border-b align-middle">O Senhor dos Anéis</td>
                    <td class="p-3 border-b align-middle font-mono text-xs text-blue-600">eyJpdiI6Ik93S...</td>
                </tr>
                <tr>
                    <td class="p-3 border-b align-middle">Harry Potter</td>
                    <td class="p-3 border-b align-middle font-mono text-xs text-blue-600">eyJpdiI6IlFvT...</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>