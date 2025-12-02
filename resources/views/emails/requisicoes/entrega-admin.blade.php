@component('mail::message')
# Livro Entregue

Uma requisição foi marcada como **entregue**.

**Cidadão:** {{ $user->name }}
**Email:** {{ $user->email }}

**Livro:** {{ $livro->nome }}
**Requisição Nº:** {{ $requisicao->numero }}

**Data de Entrega Real:**
{{ \Carbon\Carbon::parse($requisicao->data_entrega_real)->format('d/m/Y') }}

@endcomponent
