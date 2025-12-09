@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
  <h1 class="text-2xl mb-4">Pagamento</h1>

  <p>Serás redirecionado para a página segura de pagamento (Stripe).</p>

  <form id="checkout-form" action="#" method="POST">
    @csrf
    <button id="stripe-button" class="btn btn-primary">Pagar</button>
  </form>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
  const stripe = Stripe("{{ config('services.stripe.key') }}");
  document.getElementById('stripe-button').addEventListener('click', function(e){
    e.preventDefault();
    stripe.redirectToCheckout({ sessionId: "{{ $session->id }}" });
  });
</script>
@endsection
