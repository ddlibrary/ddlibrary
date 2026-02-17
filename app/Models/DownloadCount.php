<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class DownloadCount extends Model
{
    use HasFactory;
    protected $fillable = ['resource_id', 'file_id', 'user_id', 'ip_address'];
    public function getCount()
    {
        return DB::table('download_counts AS dc')
            ->select(
                'dc.resource_id',
                'rs.title',
                'rs.language',
                DB::raw('count(dc.resource_id) AS total')
            )
            ->leftJoin('resources AS rs', 'rs.id', '=', 'dc.resource_id')
            ->where('dc.created_at', '>', \Carbon\Carbon::now()->subDays(30))
            ->groupBy('resource_id', 'rs.title', 'dc.resource_id', 'rs.language')
            ->orderBy('total', 'DESC')
            ->limit(10)
            ->get();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(ResourceAttachment::class, 'file_id');
    }
}
