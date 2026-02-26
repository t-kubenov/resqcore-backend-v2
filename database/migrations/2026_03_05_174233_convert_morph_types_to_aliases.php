<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $morphMap = [
            'App\Tenant\User' => 'user',
            'App\Models\User' => 'user',
            'App\Models\Vehicle\Vehicle' => 'vehicle',
            'App\Models\Survey\Violation' => 'violation',
            'App\Models\Survey\Survey' => 'survey',
            'App\Models\Report\Report' => 'report',
            'App\Models\Report' => 'report',
            'App\Models\Report\Update' => 'update',
            'App\Models\Update' => 'update',
        ];

        $morphTableColumns = [
            'activity_log' => ['subject_type', 'causer_type'],
            'custom_field_values' => 'model_type',
            'custom_fields' => 'model',
            'images' => 'model_type',
            'model_has_permissions' => 'model_type',
            'model_has_roles' => 'model_type',
            'personal_access_tokens' => 'tokenable_type',
        ];

        foreach ($morphTableColumns as $table => $columns){
            foreach ($morphMap as $class => $alias) {
                foreach ((array) $columns as $column){
                    $this->updateColumn($table, $column, $class, $alias);
                }
            }
        }
    }

    private function updateColumn($table, $column, $class, $alias): void
    {
        DB::table($table)
            ->where($column, $class)
            ->update([$column => $alias]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aliases', function (Blueprint $table) {
            //
        });
    }
};
