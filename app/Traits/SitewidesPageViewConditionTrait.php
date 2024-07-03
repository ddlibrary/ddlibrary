<?php

namespace App\Traits;

trait SitewidesPageViewConditionTrait
{
    protected function filterPageViews($query, $request)
    {
        if ($request->platform_id) {
            $query->where('platform_id', $request->platform_id);
        }
        if ($request->gender) {
            $query->where('gender', $request->gender);
        }
        if ($request->device_id) {
            $query->where('device_id', $request->device_id);
        }
        if ($request->glossary_subject_id) {
            $query->where('glossary_subject_id', $request->glossary_subject_id);
        }
        if ($request->language) {
            $query->where('language', $request->language);
        }
        if ($request->page_type_id) {
            $query->where('page_type_id', $request->page_type_id);
        }
        if ($request->browser_id) {
            $query->where('browser_id', $request->browser_id);
        }

        if ($request->is_bot) {
            $query->where('is_bot', $request->is_bot ==1 ? true : false);
        }
        if ($request->date_from && $request->date_to) {
            $query->whereBetween('created_at', [$request->date_from . ' 00:00:00', $request->date_to . ' 23:59:59']);
        }

        return $query;
    }
}
