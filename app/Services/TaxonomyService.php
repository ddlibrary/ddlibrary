<?php

namespace App\Services;

use App\Models\TaxonomyHierarchy;
use App\Models\TaxonomyTerm;
use Illuminate\Support\Facades\DB;

class TaxonomyService
{
    public function saveTranslationTerm($vid, $name, $weight, $language, $tnid, $parentId, $termId = null): TaxonomyTerm
    {
        $term = $termId ? TaxonomyTerm::find($termId) : new TaxonomyTerm();
        $term->vid = $vid;
        $term->name = $name;
        $term->weight = $weight;
        $term->language = $language;
        $term->tnid = $tnid ?? 0;
        $term->save();

        $hierarchy = TaxonomyHierarchy::where('tid', $term->id)->first();

        if ($hierarchy) {
            $hierarchy->parent = $parentId;
            $hierarchy->save();
        } else {
            TaxonomyHierarchy::create([
                'id' => 0,
                'tid' => $term->id,
                'parent' => $parentId,
            ]);
            TaxonomyHierarchy::where(['tid' => $term->id, 'parent' => $parentId])->update(['id' => DB::raw('aux_id')]);
        }

        return $term;
    }
}
