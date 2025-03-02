<?php

namespace App\Http\Controllers\V2;

use App\Enums\CustomFieldType;
use App\Http\Requests\V2\CustomField\StoreCustomFieldRequest;
use App\Models\V2\CustomField\CustomField;

class CustomFieldController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomFieldRequest $request)
    {
        $customField = CustomField::create($request->validated());

        if (in_array($request['type'],[
                CustomFieldType::SingleChoiceEnum->value,
                CustomFieldType::MultipleChoiceEnum->value
                ])
            && $request->has('options')) {
            $this->storeCustomFieldOptions($customField, $request->options);
        }

        if ($request->has('subfields')) {
            $this->storeSubfields($customField, $request->subfields);
        }


        return response()->json($customField, 201);
    }

    private function storeCustomFieldOptions($customField, $options)
    {
        foreach ($options as $option) {
            $option['custom_field_id'] = $customField->id;
            $customField->options()->create($option);
        }
    }

    private function storeSubfields($customField, $subfields)
    {
        foreach ($subfields as $subfield) {
            $subfield['parent_field'] = $customField->id;
            $customField->subfields()->create($subfield);
        }
    }
}
