<?php

namespace App\Services;

use App\Models\TaxonomyHierarchy;
use App\Models\TaxonomyTerm;
use App\Models\TaxonomyVocabulary;
use Illuminate\Support\Collection;

class TaxonomyService
{
    /**
     * Get grouped taxonomy terms with hierarchy for a given vocabulary
     *
     * @param int $vocabularyId
     * @param string|null $termName
     * @return array{groupedTerms: array, parentInfo: array}
     */
    public function getGroupedTermsWithHierarchy(int $vocabularyId, ?string $termName = null): array
    {
        // Build base query
        $baseQuery = TaxonomyTerm::where('vid', $vocabularyId);
        
        if ($termName) {
            $baseQuery->where('name', 'like', '%' . $termName . '%');
        }

        // Get distinct tnids (grouping by tnid)
        $tnids = (clone $baseQuery)
            ->whereNotNull('tnid')
            ->where('tnid', '!=', 0)
            ->distinct()
            ->pluck('tnid')
            ->toArray();

        // Get all translations grouped by tnid - use eager loading to avoid N+1 queries
        $groupedTerms = [];
        if (!empty($tnids)) {
            // Fetch all terms with these tnids in a single query, eager load vocabulary relationship
            $allTranslations = TaxonomyTerm::with('vocabulary')
                ->whereIn('tnid', $tnids)
                ->where('vid', $vocabularyId)
                ->orderBy('weight')
                ->get();
            
            // Group by tnid in memory
            $translationsByTnid = $allTranslations->groupBy('tnid');
            
            // Sort tnids to maintain order and build groupedTerms
            foreach ($tnids as $tnid) {
                if ($translationsByTnid->has($tnid)) {
                    $groupedTerms[] = $translationsByTnid->get($tnid);
                }
            }
        }

        // Also include terms with tnid = 0 or null (no translations) - eager load vocabulary
        $singleTerms = (clone $baseQuery)
            ->with('vocabulary')
            ->where(function($q) {
                $q->whereNull('tnid')
                  ->orWhere('tnid', 0);
            })
            ->get();

        // Add single terms (no translations) to the list
        foreach ($singleTerms as $singleTerm) {
            $groupedTerms[] = collect([$singleTerm]);
        }
        
        // Also include terms with invalid language values (NULL, empty, or 'und') - eager load vocabulary
        $invalidLanguageTerms = (clone $baseQuery)
            ->with('vocabulary')
            ->where(function($q) {
                $q->whereNull('language')
                  ->orWhere('language', '')
                  ->orWhere('language', 'und');
            })
            ->where(function($q) {
                $q->whereNull('tnid')
                  ->orWhere('tnid', 0);
            })
            ->get();
        
        foreach ($invalidLanguageTerms as $invalidTerm) {
            // Check if this term is not already in groupedTerms
            $exists = false;
            foreach ($groupedTerms as $group) {
                if ($group->contains('id', $invalidTerm->id)) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                $groupedTerms[] = collect([$invalidTerm]);
            }
        }

        // Load parent information
        $parentInfo = $this->loadParentInfo($groupedTerms);

        // Build hierarchical structure
        $hierarchicalTerms = $this->buildHierarchicalStructure($groupedTerms, $parentInfo);

        return [
            'groupedTerms' => $hierarchicalTerms,
            'parentInfo' => $parentInfo
        ];
    }

    /**
     * Load parent information for all terms
     *
     * @param array $groupedTerms
     * @return array
     */
    protected function loadParentInfo(array $groupedTerms): array
    {
        $parentInfo = [];
        $termIdsToCheck = [];
        
        foreach ($groupedTerms as $translations) {
            $firstTerm = $translations->first();
            $termIdsToCheck[] = $firstTerm->id;
        }
        
        if (empty($termIdsToCheck)) {
            return $parentInfo;
        }

        $hierarchies = TaxonomyHierarchy::whereIn('tid', $termIdsToCheck)->get();
        $parentTermIds = $hierarchies->pluck('parent')->filter(function($parent) {
            return $parent > 0;
        })->unique()->toArray();
        
        // Load parent term names (get first translation of each parent) - use eager loading
        $parentTerms = [];
        if (!empty($parentTermIds)) {
            $parentTermData = TaxonomyTerm::whereIn('id', $parentTermIds)->get();
            
            // Collect all parent tnids (including those that use their own id as tnid)
            $parentTnids = [];
            $parentIdToTnid = [];
            
            foreach ($parentTermData as $parentTerm) {
                $parentTnid = $parentTerm->tnid ?? $parentTerm->id;
                $parentTnids[] = $parentTnid;
                $parentIdToTnid[$parentTerm->id] = $parentTnid;
            }
            
            // Fetch all parent translations in a single query
            $parentTnids = array_unique($parentTnids);
            $allParentTranslations = TaxonomyTerm::where(function($q) use ($parentTnids) {
                $q->whereIn('tnid', $parentTnids)
                  ->orWhereIn('id', $parentTnids);
            })->get();
            
            // Group by tnid (or id if tnid is null/0)
            $translationsByTnid = $allParentTranslations->groupBy(function($term) {
                return $term->tnid && $term->tnid != 0 ? $term->tnid : $term->id;
            });
            
            // Map parent IDs to their names
            foreach ($parentTermData as $parentTerm) {
                $parentTnid = $parentIdToTnid[$parentTerm->id];
                $parentTranslations = $translationsByTnid->get($parentTnid, collect());
                
                // Prefer English, then first available
                $parentName = $parentTranslations->where('language', 'en')->first();
                if (!$parentName) {
                    $parentName = $parentTranslations->first();
                }
                $parentTerms[$parentTerm->id] = $parentName ? $parentName->name : 'Unknown';
            }
        }
        
        // Map each term to its parent (store by first term id for each group)
        foreach ($hierarchies as $hierarchy) {
            if ($hierarchy->parent > 0) {
                $parentInfo[$hierarchy->tid] = [
                    'parent_id' => $hierarchy->parent,
                    'parent_name' => $parentTerms[$hierarchy->parent] ?? 'Unknown'
                ];
            } else {
                $parentInfo[$hierarchy->tid] = [
                    'parent_id' => 0,
                    'parent_name' => null
                ];
            }
        }

        return $parentInfo;
    }

    /**
     * Build hierarchical structure with parents before children
     *
     * @param array $groupedTerms
     * @param array $parentInfo
     * @return array
     */
    protected function buildHierarchicalStructure(array $groupedTerms, array $parentInfo): array
    {
        $hierarchicalTerms = [];
        $processed = [];
        $termMap = []; // Map term ID to grouped term index
        
        // Build term map
        foreach ($groupedTerms as $index => $translations) {
            $firstTerm = $translations->first();
            $termMap[$firstTerm->id] = $index;
        }
        
        // Helper function to add term and its children recursively
        $addTermRecursive = function($termId, $level = 0) use (&$addTermRecursive, &$hierarchicalTerms, &$processed, &$groupedTerms, &$termMap, &$parentInfo) {
            if (isset($processed[$termId])) {
                return; // Already processed
            }
            
            // Find all children of this term
            $children = [];
            foreach ($parentInfo as $tid => $info) {
                if ($info['parent_id'] == $termId && !isset($processed[$tid])) {
                    $children[] = $tid;
                }
            }
            
            // If this is a root term (parent_id = 0), add it
            if ($termId > 0 && isset($termMap[$termId])) {
                $groupedTerm = $groupedTerms[$termMap[$termId]];
                $hierarchicalTerms[] = [
                    'translations' => $groupedTerm,
                    'level' => $level,
                    'parent_id' => $parentInfo[$termId]['parent_id'] ?? 0
                ];
                $processed[$termId] = true;
            }
            
            // Sort children by weight and add them recursively
            usort($children, function($a, $b) use ($termMap, $groupedTerms) {
                $termA = $groupedTerms[$termMap[$a]]->first();
                $termB = $groupedTerms[$termMap[$b]]->first();
                $weightA = $termA->weight ?? 0;
                $weightB = $termB->weight ?? 0;
                return $weightA <=> $weightB;
            });
            
            foreach ($children as $childId) {
                $addTermRecursive($childId, $level + 1);
            }
        };
        
        // First, add all top-level terms (parent_id = 0)
        $topLevelTerms = [];
        foreach ($groupedTerms as $index => $translations) {
            $firstTerm = $translations->first();
            $parentData = $parentInfo[$firstTerm->id] ?? ['parent_id' => 0];
            if ($parentData['parent_id'] == 0) {
                $topLevelTerms[] = $firstTerm->id;
            }
        }
        
        // Sort top-level terms by weight
        usort($topLevelTerms, function($a, $b) use ($termMap, $groupedTerms) {
            $termA = $groupedTerms[$termMap[$a]]->first();
            $termB = $groupedTerms[$termMap[$b]]->first();
            $weightA = $termA->weight ?? 0;
            $weightB = $termB->weight ?? 0;
            return $weightA <=> $weightB;
        });
        
        // Add all top-level terms and their children recursively
        foreach ($topLevelTerms as $termId) {
            $addTermRecursive($termId, 0);
        }
        
        // Add any remaining terms that weren't processed (orphaned terms)
        foreach ($groupedTerms as $index => $translations) {
            $firstTerm = $translations->first();
            if (!isset($processed[$firstTerm->id])) {
                $parentData = $parentInfo[$firstTerm->id] ?? ['parent_id' => 0];
                $hierarchicalTerms[] = [
                    'translations' => $translations,
                    'level' => 0,
                    'parent_id' => $parentData['parent_id']
                ];
            }
        }

        return $hierarchicalTerms;
    }
}

