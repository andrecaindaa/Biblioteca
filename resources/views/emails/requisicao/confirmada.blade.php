@component('mail::message')
# Confirmação de Requisição

Olá {{ $user->name }},

Sua requisição do livro **{{ $livro->nome }}** foi realizada com sucesso!

- **Nº da Requisição:** {{ $requisicao->numero }}
- **Data da Requisição:** {{ $requisicao->data_requisicao->format('d/m/Y') }}
- **Data Prevista de Entrega:** {{ $requisicao->data_prevista_entrega->format('d/m/Y') }}

@if($livro->capa)
![Capa do Livro]({{ asset('storage/' . $livro->capa) }})
@endif

Obrigado,<br>
**Biblioteca**
@endcomponent
