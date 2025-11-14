<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class TwoFactorQrCode extends Component
{
    public $qrCodeUrl;

    public function mount()
    {
        if (Auth::user()->two_factor_secret) {
            $this->generateQrCode();
        }
    }

    private function generateQrCode()
    {
        try {
            $google2fa = new \PragmaRX\Google2FAQRCode\Google2FA();

            $this->qrCodeUrl = $google2fa->getQRCodeInline(
                config('app.name'),
                Auth::user()->email,
                decrypt(Auth::user()->two_factor_secret)
            );
        } catch (\Exception $e) {
            $this->qrCodeUrl = null;
        }
    }

    public function render()
    {
        return view('livewire.two-factor-qr-code');
    }
}
