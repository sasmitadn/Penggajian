<?php

namespace App\Helpers;

class LicenseChecker {
    public static function isValid(): bool
    {
        $secret = config('app.key');
        $file = base_path('.license');

        if (!file_exists($file)) return false;

        $encrypted = file_get_contents($file);
        $decrypted = openssl_decrypt(base64_decode($encrypted), 'AES-256-CBC', $secret, 0, substr($secret, 0, 16));

        if (!$decrypted) return false;

        $data = json_decode($decrypted, true);

        if (!$data || !isset($data['expired']) || !isset($data['domain'])) return false;

        if (now()->gt($data['expired'])) return false;

        if (request()->getHost() !== $data['domain']) return true;

        return true;
    }
}
