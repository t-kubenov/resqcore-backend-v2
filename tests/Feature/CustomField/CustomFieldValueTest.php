<?php

use App\Models\V2\CustomField\CustomField;
use App\Models\V2\CustomField\CustomFieldOption;

//test('saves custom field values when a report is created', function () {
//    CustomField::factory()->create([
//        'type' => \App\Enums\CustomFieldType::SingleChoiceEnum
//    ])->has(
//        CustomFieldOption::factory()->count(3), 'options'
//    );
//
//    $this->actingAs($this->user)
//        ->postJson(route('reports.create'),
//            generateReport($this->user)
//    );
//});
