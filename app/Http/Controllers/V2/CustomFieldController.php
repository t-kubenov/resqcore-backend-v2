<?php

namespace App\Http\Controllers\V2;

use App\Enums\CustomFieldParameter;
use App\Enums\CustomFieldType;
use App\Http\Requests\V2\CustomField\StoreCustomFieldRequest;
use App\Models\V2\CustomField\CustomField;
use App\Models\V2\Report\Update;


class CustomFieldController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomFieldRequest $request)
    {
        $attributes = $request->validated();
        $customField = $this->createWithAttributes($attributes);

        return response()->json($customField, 201);
    }


    private function createWithAttributes(array $attributes): CustomField
    {
        $customField = CustomField::create($attributes);

        if (in_array($attributes['type'],[
                CustomFieldType::SingleChoiceEnum->value,
                CustomFieldType::MultipleChoiceEnum->value
            ])
            && isset($attributes['options'])) {
            $this->storeCustomFieldOptions($customField, $attributes['options']);
        }

        if (isset($attributes['subfields'])) {
            $this->storeSubfields($customField, $attributes['subfields']);
        }

        if (isset($attributes['parameters']) && in_array(CustomFieldParameter::IncludeToUpdates->value, $attributes['parameters'])) {
            $this->createWithAttributes(array_merge($attributes, [
                'model' => Update::class,
                'parameters' => [],
            ]));
        }

        //todo: total fields and addition

        return $customField;
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
