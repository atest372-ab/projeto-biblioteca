<div style="font-family: sans-serif; padding: 20px; color: #333;">
    <h1 style="color: #2563eb;">Olá, {{ $requisicao->user->name }}!</h1>
    <p>A tua requisição do livro <strong>{{ $requisicao->book->name }}</strong> foi confirmada com sucesso.</p>
    
    <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; margin: 20px 0;">
        <p style="margin: 0;"><strong>Data de Requisição:</strong> {{ now()->format('d/m/Y') }}</p>
        <p style="margin: 0; color: #dc2626;"><strong>Data Limite de Entrega:</strong> {{ now()->addDays(5)->format('d/m/Y') }} (Prazo de 5 dias)</p>
    </div>

    <p>Por favor, garante a entrega dentro do prazo para evitar bloqueios na tua conta Inovcorp.</p>
    <hr>
    <small>Este é um e-mail automático gerado pelo Sistema de Biblioteca Inovcorp.</small>
</div>