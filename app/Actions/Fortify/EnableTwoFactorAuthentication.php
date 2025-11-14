<?php

use PragmaRX\Google2FA\Google2FA;

$google2fa = new Google2FA();
$secret = $google2fa->generateSecretKey();
$user->forceFill([
    'two_factor_secret' => encrypt($secret),
    'two_factor_recovery_codes' => encrypt(json_encode(['recovery-code-1','recovery-code-2'])),
])->save();
