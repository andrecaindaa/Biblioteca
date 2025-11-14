<div>
    @if($qrCodeUrl)
        <div class="mb-4">
            <p class="font-semibold mb-2">Escaneie este QR Code:</p>
            <img src="{{ $qrCodeUrl }}" alt="QR Code 2FA" class="w-48 h-48 border rounded-lg">
        </div>
    @endif
</div>
