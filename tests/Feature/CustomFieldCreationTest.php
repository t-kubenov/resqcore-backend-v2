<?php

use App\Models\RescueCompany;
use App\Enums\CustomFieldType;
use App\Models\User;
use App\Models\V2\CustomField\CustomField;


beforeEach(function () {
    $this->rescueCompany = RescueCompany::where('key', 'test')->first();
    $this->user = User::where('role_id', '1')->first();
    $this->url = '/api/v2/admin/custom-fields';
});

//todo: add tests for successful creation of different field types, check db, etc.

test('custom field create', function () {

    $response = $this
        ->actingAs($this->user)
        ->postJson($this->url, [
            'rescue_company_id' => $this->rescueCompany->id,
            'name' => 'custom_field_example',
            'label' => 'Custom Field Example',
            'model' => 'Report',
            'required' => true,
            'type' => CustomFieldType::String->value,
        ]);

    $response->assertStatus(201); // Should pass and create the field

    $this->assertDatabaseHas('custom_fields', [
        'rescue_company_id' => $this->rescueCompany->id,
        'name' => 'custom_field_example',
        'label' => 'Custom Field Example',
        'model' => 'Report',
        'required' => true,
        'type' => CustomFieldType::String->value,
    ]);

    CustomField::destroy($response->json('id'));
});

it('fails when required fields are missing', function () {
    $response = $this
        ->actingAs($this->user)
        ->postJson($this->url, []);

    $response->assertStatus(422) // Validation fails
    ->assertJsonValidationErrors(['rescue_company_id', 'name', 'model', 'type']);
});


it('fails when type is invalid', function () {
    $response = $this
        ->actingAs($this->user)
        ->postJson($this->url, [
            'rescue_company_id' => $this->rescueCompany->id,
            'name' => 'custom_field_example',
            'model' => 'user',
            'required' => true,
            'type' => 'invalid_type', // ❌ Invalid type
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['type']);
});

it('requires options for single and multiple choice enums', function () {
    $response = $this
        ->actingAs($this->user)
        ->postJson($this->url, [
        'rescue_company_id' => $this->rescueCompany->id,
        'name' => 'custom_field_example',
        'model' => 'Report',
        'required' => true,
        'type' => CustomFieldType::SingleChoiceEnum->value,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['options']);
});

it('fails when MultiValue fields contain subfields of type MultiValue', function () {

    $response = $this
        ->actingAs($this->user)
        ->postJson($this->url, [
        'rescue_company_id' => $this->rescueCompany->id,
        'name' => 'multi_value_field',
        'model' => 'Report',
        'required' => true,
        'type' => CustomFieldType::MultiValue->value,
        'subfields' => [
            [
                'name' => 'invalid_subfield',
                'label' => 'Invalid Subfield',
                'type' => CustomFieldType::MultiValue->value, // ❌ Not allowed
                'required' => false,
            ],
        ],
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['subfields.0.type']);
});

