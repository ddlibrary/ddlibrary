<?php

namespace App\Services;

use Aws\CloudFront\CloudFrontClient;

class CloudFrontService
{
    protected CloudFrontClient $client;
    protected string $domain;
    protected string $keyPairId;
    protected string $privateKeyPath;

    public function __construct()
    {
        $this->client = new CloudFrontClient([
            'region' => 'us-east-1',
            'version' => 'latest',
        ]);

        $this->domain = config('services.cloudfront.domain');
        $this->keyPairId = config('services.cloudfront.key_pair_id');
        $this->privateKeyPath = base_path(config('services.cloudfront.private_key_path'));
    }

    public function signedUrl(string $path, int $expiresInSeconds = 3600, $forceDownload = false): string
    {
        $expires = time() + $expiresInSeconds;

        $unsignedUrl = sprintf('https://%s/%s', $this->domain, ltrim($path, '/'));

        return $this->client->getSignedUrl([
            'url'         => $unsignedUrl,
            'expires'     => $expires,
            'key_pair_id' => $this->keyPairId,
            'private_key' => $this->privateKeyPath,
        ]);
    }
}

