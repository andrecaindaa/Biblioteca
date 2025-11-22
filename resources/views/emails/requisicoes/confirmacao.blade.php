@component('mail::message')
# Entrega Confirmada

A entrega do livro **{{ $requisicao->livro->nome }}** foi confirmada.

**Número da Requisição:** {{ $requisicao->numero }}
**Cidadão:** {{ $requisicao->user->name }}
**Data Requisição:** {{ $requisicao->data_requisicao->format('d/m/Y') }}
**Data Real de Entrega:** {{ $requisicao->data_entrega_real->format('d/m/Y') }}

@component('mail::button', ['url' => route('requisicoes.index')])
Ver Requisições
@endcomponent

Obrigado,<br>
📚 Biblioteca
@endcomponent
