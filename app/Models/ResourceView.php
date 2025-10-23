<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Relations\BelongsToResource;
use App\Models\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ResourceView extends Model
{
    use HasFactory;

    use BelongsToResource, BelongsToUser;

    public function getCount(): Collection
    {
        return DB::table('resource_views AS rv')
            ->select(
                'rv.resource_id',
                'rs.title',
                'rs.language',
                DB::raw('count(rv.resource_id) AS total')
            )
            ->leftJoin('resources AS rs', 'rs.id', '=', 'rv.resource_id')
            ->where('rv.created_at', '>', \Carbon\Carbon::now()->subDays(30))
            ->groupBy('resource_id', 'rs.title', 'rv.resource_id', 'rs.language')
            ->orderBy('total', 'DESC')
            ->limit(10)
            ->get();
    }

    public function resourceCount($resource_id = 0)
    {
        return DB::table('resource_views')
            ->select(
                DB::raw('count(resource_id) AS total')
            )
            ->where('resource_id', $resource_id)
            ->first()->total;
    }
}
