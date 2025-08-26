<?php

use App\Models\CustomField\CustomField;
use App\Models\CustomField\CustomFieldOption;

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
