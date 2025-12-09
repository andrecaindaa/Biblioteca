<div class="card bg-base-100 shadow-xl p-6">
    <h2 class="card-title text-2xl mb-4">
        Autenticação de Dois Fatores (2FA)
    </h2>

    {{-- Estado atual --}}
    <p class="text-sm opacity-70 mb-6">
        @if ($this->enabled)
            @if ($showingConfirmation)
                Para concluir a ativação, introduz o código gerado pela app Authenticator.
            @else
                A autenticação de dois fatores está <span class="text-success font-bold">ativa</span>.
            @endif
        @else
            A autenticação de dois fatores está <span class="text-warning font-bold">desativada</span>.
        @endif
    </p>

    {{-- 2FA ATIVO --}}
    @if ($this->enabled)

        {{-- Mostrar QR CODE --}}
        @if ($showingQrCode)
            <div class="alert alert-info shadow mb-4">
                <span class="font-semibold">
                    @if ($showingConfirmation)
                        Scaneia este QR Code na tua aplicação de autenticação e insere o código abaixo.
                    @else
                        Scaneia este QR Code para configurar novamente o 2FA.
                    @endif
                </span>
            </div>

            <div class="p-4 bg-white rounded-lg inline-block mx-auto mb-4">
                {!! $this->user->twoFactorQrCodeSvg() !!}
            </div>

            <div class="mb-6">
                <p class="font-semibold mb-2">🔑 Chave de Configuração:</p>
                <div class="badge badge-outline badge-lg p-4">
                    {{ decrypt($this->user->two_factor_secret) }}
                </div>
            </div>
        @endif

        {{-- Campo para confirmar código --}}
        @if ($showingConfirmation)
            <div class="mb-4">
                <label class="label">
                    <span class="font-semibold">Código de Verificação</span>
                </label>
                <input type="text"
                       wire:model="code"
                       wire:keydown.enter="confirmTwoFactorAuthentication"
                       class="input input-bordered w-full max-w-xs"
                       maxlength="6"
                       inputmode="numeric"
                       autofocus>

                @error('code')
                    <p class="text-error text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
        @endif

        {{-- Mostrar Recovery Codes --}}
        @if ($showingRecoveryCodes)
            <div class="mb-4">
                <p class="font-semibold mb-2">🆘 Códigos de Recuperação:</p>

                <div class="bg-base-200 p-4 rounded-lg grid grid-cols-1 sm:grid-cols-2 gap-2">
                    @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                        <span class="badge badge-outline p-3 text-sm">{{ $code }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Botões --}}
        <div class="flex flex-wrap gap-3 mt-6">

            @if ($showingConfirmation)
                {{-- Finalizar ativação --}}
                <button wire:click="confirmTwoFactorAuthentication"
                        class="btn btn-success">
                    Confirmar Ativação
                </button>
            @else
                {{-- Mostrar códigos --}}
                <button wire:click="showRecoveryCodes" class="btn btn-secondary">
                    Ver Códigos
                </button>

                {{-- Regenerar códigos --}}
                <button wire:click="regenerateRecoveryCodes" class="btn btn-accent">
                    Regenerar Códigos
                </button>
            @endif

            {{-- Desativar 2FA --}}
            <button wire:click="disableTwoFactorAuthentication"
                    class="btn btn-error ml-auto">
                Desativar 2FA
            </button>
        </div>

    {{-- 2FA DESATIVADO --}}
    @else
        <div class="alert alert-warning shadow mb-6">
            <span>2FA está desativado. Recomendamos ativar para maior segurança.</span>
        </div>

        <button wire:click="enableTwoFactorAuthentication"
                class="btn btn-primary">
            Ativar 2FA
        </button>
    @endif
</div>
