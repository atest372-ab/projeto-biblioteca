{{-- resources/views/emails/cart_reminder.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lembrete do Carrinho</title>
</head>
<body>
    <h1>Olá, {{ $user->name }}!</h1>

    <p>Você tem itens no seu carrinho que estão aguardando para serem finalizados.</p>

    <p>Não perca a chance de adquirir estes livros:</p>

    <ul>
        @foreach($carts as $cart)
            <li>{{ $cart->book->name }} - {{ $cart->book->authors->first()->name ?? 'Autor desconhecido' }}</li>
        @endforeach
    </ul>

    <p><a href="{{ url('/cart') }}">Clique aqui para ver seu carrinho</a></p>

    <p>Atenciosamente,<br>
    Equipe da Biblioteca</p>
</body>
</html>