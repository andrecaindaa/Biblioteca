<h2>Nova Requisição Criada</h2>

<p>Um cidadão fez uma nova requisição:</p>

<p><strong>Utilizador:</strong> {{ $user->name }}</p>
<p><strong>Email:</strong> {{ $user->email }}</p>

<p><strong>Livro:</strong> {{ $livro->nome }}</p>
<p><strong>ISBN:</strong> {{ $livro->isbn }}</p>

<p><strong>Data Prevista de Entrega:</strong> {{ $requisicao->data_prevista_entrega }}</p>
