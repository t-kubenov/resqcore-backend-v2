<?php

use App\Models\RescueCompany;
use App\Enums\CustomFieldType;
use App\Models\User;
use App\Models\V2\CustomField\CustomField;


beforeEach(function () {
    $this->rescueCompany = RescueCompany::inRandomOrder()->first();
    $this->user = User::where('role_id', '1')->first();
    $this->url = '/api/v2/admin/custom-fields';
});

//todo: add tests for successful creation of different field types, check db for existence of the field, values and options.
//todo: add tests for storing data into a registered custom field, check for the storage of value.

test('custom field create', function () {

    $name = fake()->word();

    $response = $this
        ->actingAs($this->user)
        ->postJson($this->url, [
            'rescue_company_id' => $this->rescueCompany->id,
            'name' => $name,
            'label' => 'Custom Field Example',
            'model' => 'App\Models\Report',
            'required' => true,
            'type' => CustomFieldType::String->value,
        ]);

    $response->assertStatus(201); // Should pass and create the field

    $this->assertDatabaseHas('custom_fields', [
        'rescue_company_id' => $this->rescueCompany->id,
        'name' => $name,
        'label' => 'Custom Field Example',
        'model' => 'App\Models\Report',
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
            'type' => 'invalid_type',
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['type']);
});


it('requires options for single and multiple choice enums', function () {
    $this
        ->actingAs($this->user)
        ->postJson($this->url, [
            'rescue_company_id' => $this->rescueCompany->id,
            'name' => 'custom_field_example',
            'model' => 'App\Models\Report',
            'required' => true,
            'type' => CustomFieldType::SingleChoiceEnum->value,
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['options']);
});


it('stores custom field options when enum type is created', function()
{
    $options = [
        [
            'name' => fake()->word(),
            'label' => fake()->sentence()
        ],
        [
            'name' => fake()->word(),
            'label' => fake()->sentence()
        ],
    ];

    $this
        ->actingAs($this->user)
        ->postJson($this->url, [
            'rescue_company_id' => $this->rescueCompany->id,
            'name' => fake()->word(),
            'model' => 'App\Models\Report',
            'required' => false,
            'type' => CustomFieldType::SingleChoiceEnum->value,
            'options' => $options
        ]);

        foreach ($options as $option)
        {
            $this->assertDatabaseHas('custom_field_options', $option);
        }
});


it('fails when MultiValue fields contain subfields of type MultiValue', function () {

    $response = $this
        ->actingAs($this->user)
        ->postJson($this->url, [
        'rescue_company_id' => $this->rescueCompany->id,
        'name' => 'multi_value_field',
        'model' => 'App\Models\Report',
        'required' => true,
        'type' => CustomFieldType::MultiValue->value,
        'subfields' => [
            [
                'name' => 'invalid_subfield',
                'label' => 'Invalid Subfield',
                'type' => CustomFieldType::MultiValue->value, // not allowed
                'required' => false,
            ],
        ],
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['subfields.0.type']);
});
