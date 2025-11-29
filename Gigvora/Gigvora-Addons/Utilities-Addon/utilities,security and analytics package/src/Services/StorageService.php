<?php

namespace ProNetwork\Services;

use Illuminate\Support\Facades\Storage;

class StorageService
{
    public function disk(?string $disk = null)
    {
        $disk = $disk ?? config('pro_network_utilities_security_analytics.storage.default_disk', 'local');

        return Storage::disk($disk);
    }

    public function secureUrl(string $path, ?string $disk = null, int $ttl = null): string
    {
        $ttl = $ttl ?? (int) config('pro_network_utilities_security_analytics.storage.signed_url_ttl', 300);
        return $this->disk($disk)->temporaryUrl($path, now()->addSeconds($ttl));
    }

    public function putEncrypted(string $path, string $contents, ?string $disk = null): string
    {
        $disk = $disk ?? config('pro_network_utilities_security_analytics.storage.default_disk', 'local');
        $key = config('pro_network_utilities_security_analytics.storage.encryption.key');

        if (config('pro_network_utilities_security_analytics.storage.encryption.enabled') && $key) {
            $contents = openssl_encrypt($contents, 'aes-256-cbc', $key, 0, substr($key, 0, 16));
        }

        $this->disk($disk)->put($path, $contents);

        return $path;
    }
}
