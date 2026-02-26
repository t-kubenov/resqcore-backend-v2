<?php

namespace App\Filter\Configurations;

use App\Filter\Filters\DateRangeFilter;
use App\Filter\Filters\RelationSearchFilter;
use App\Filter\Filters\SearchFilter;
use App\Filter\Filters\WhereFilter;
use App\Filter\Filters\WhereInFilter;

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
