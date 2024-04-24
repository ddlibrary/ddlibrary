<?php

namespace App\Traits;

use App\Models\DownloadCount;

trait DownloadCountTrait
{
    public function storeDownloadCount($resource_id, $file_id): void
    {
        if (is_numeric($resource_id) && is_numeric($file_id)) {
            DownloadCount::insert([
                'resource_id' => $resource_id,
                'file_id' => $file_id,
                'user_id' => auth()->id() ? auth()->id() : 0,
                'ip_address' => request()->ip(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
