<?php

namespace App\Livewire;

use Livewire\Component;
use PragmaRX\Google2FAQRCode\Google2FA;

class TwoFactorSettings extends Component
{
    public $user;
    public $qrCode;

    public function mount($user)
    {
        $this->user = $user;

        // Se o usuário já tem 2FA ativo, gera o QR Code
        if ($this->user->two_factor_secret) {
            $this->generateQrCode();
        }
    }

    public function enableTwoFactor()
    {
        $google2fa = new Google2FA();

        $secret = $google2fa->generateSecretKey();

        $this->user->forceFill([
            'two_factor_secret' => encrypt($secret),
            'two_factor_recovery_codes' => encrypt(json_encode([
                'recovery-code-1',
                'recovery-code-2',
                'recovery-code-3',
                'recovery-code-4',
                'recovery-code-5',
                'recovery-code-6'
            ])),
        ])->save();

        $this->generateQrCode();

        session()->flash('message', '2FA ativado! Escaneie o QR Code no Google Authenticator.');
    }

    public function disableTwoFactor()
    {
        $this->user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
        ])->save();

        $this->qrCode = null;
        session()->flash('message', '2FA desativado!');
    }

    private function generateQrCode()
    {
        $google2fa = new Google2FA();

        $this->qrCode = $google2fa->getQRCodeInline(
            config('app.name'),
            $this->user->email,
            decrypt($this->user->two_factor_secret)
        );
    }

    public function render()
    {
        return view('livewire.two-factor-settings');
    }
}
