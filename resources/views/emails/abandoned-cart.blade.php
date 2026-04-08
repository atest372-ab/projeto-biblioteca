<h1>Olá, {{ $user->name }}!</h1>
<p>Reparei que deixou alguns livros no seu carrinho na InovCorp.</p>
<p>Precisa de ajuda para finalizar a sua encomenda?</p>
<a href="{{ url('/carrinho') }}">Voltar ao Carrinho</a>