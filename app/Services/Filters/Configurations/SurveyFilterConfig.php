<?php

namespace App\Services\Filters\Configurations;

use App\Services\Filters\Filters\DateRangeFilter;
use App\Services\Filters\Filters\RelationSearchFilter;
use App\Services\Filters\Filters\SearchFilter;
use App\Services\Filters\Filters\WhereFilter;
use App\Services\Filters\Filters\WhereInFilter;

class SurveyFilterConfig extends BaseFilterConfig
{
    protected function filters(): array
    {
        return [
            'branch_office_id' => new WhereInFilter('branch_office_id'),
            'created_by' => new WhereInFilter('created_by'),
            'senders' => new RelationSearchFilter('senders', 'name'),
            'spokesmen' => new RelationSearchFilter('spokesmen', 'name'),
            'reviewer' => new RelationSearchFilter('reviewer', 'name'),
            'survey_date' => new DateRangeFilter('survey_date'),
            'is_closed' => new WhereFilter('is_closed'),
            'search' => new SearchFilter([
                ['violations', 'description'],
                ['violations', 'clause'],
                ['legal_entity', 'name'],
                ['legal_entity_unit', 'name'],
                'hazardous_facility_name',
                'survey_code',
            ])
        ];
    }
}
