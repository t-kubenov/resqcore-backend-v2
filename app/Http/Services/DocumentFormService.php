<?php

namespace App\Http\Services;

use App\Enums\FieldParameter;
use App\Models\Sushi\ReportFieldCategory;

class DocumentFormService
{
    public function getCategorizedCreateFields($model): array
    {
        $fields = $model::fieldDefinitions();

        $fields = array_filter($fields, function ($field) {
            return !in_array(FieldParameter::IsHidden, $field['parameters'] ?? []);
        });

        // Group fields by category
        $groupedFields = [];

        foreach ($fields as $field) {
            $category = ReportFieldCategory::find($field['parameters'][FieldParameter::CategoryId->value] ?? 2);
            $catName = $category->name;

            if (!isset($groupedFields[$catName])) {
                $groupedFields[$catName] = [
                    'category' => $catName,
                    'order' => $category->id,
                    ...(isset($category->note) ? ['note' => $category->note] : []),
                    'fields' => []
                ];
            }

            $groupedFields[$catName]['fields'][] = $field;
        }

        usort($groupedFields, function ($a, $b) {
            return $a['order'] <=> $b['order'];
        });

        return array_values($groupedFields);
    }
}
