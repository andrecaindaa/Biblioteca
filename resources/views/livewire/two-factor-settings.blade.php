<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <h4 class="card-title text-xl mb-4">Autenticação de Dois Fatores (2FA)</h4>

        @if ($user->two_factor_secret)
            <p class="text-success font-semibold mb-4">✅ 2FA Ativo</p>

            <p class="mb-2 font-semibold">Escaneie este QR Code no Google Authenticator:</p>

            @if ($qrCode)
                <div class="mb-4">
                    <img src="{{ $qrCode }}" alt="QR Code 2FA" class="w-48 h-48">
                </div>
            @endif

            <p class="mb-2 font-semibold">Códigos de Recuperação:</p>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mb-4">
                @foreach (json_decode(decrypt($user->two_factor_recovery_codes), true) as $code)
                    <span class="badge badge-outline">{{ $code }}</span>
                @endforeach
            </div>

            <form method="POST" wire:submit.prevent="disableTwoFactor">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-error btn-sm lg:btn-md">Desativar 2FA</button>
            </form>

        @else
            <p class="text-warning font-semibold mb-4">⚠️ 2FA Não configurado</p>
            <form method="POST" wire:submit.prevent="enableTwoFactor">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm lg:btn-md">Ativar 2FA</button>
            </form>
        @endif
    </div>
</div>
