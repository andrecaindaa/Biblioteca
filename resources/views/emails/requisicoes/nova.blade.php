@component('mail::message')
# Nova Requisição de Livro

O livro **{{ $requisicao->livro->nome }}** foi requisitado por **{{ $requisicao->user->name }}**.

**Número da Requisição:** {{ $requisicao->numero }}
**Data da Requisição:** {{ $requisicao->data_requisicao->format('d/m/Y') }}
**Data Prevista de Entrega:** {{ $requisicao->data_prevista_entrega->format('d/m/Y') }}

@if($requisicao->foto_cidadao)
![Foto do Cidadão]({{ asset('storage/' . $requisicao->foto_cidadao) }})
@endif

@component('mail::button', ['url' => route('requisicoes.index')])
Ver Requisições
@endcomponent

Obrigado,<br>
📚 Biblioteca
@endcomponent
