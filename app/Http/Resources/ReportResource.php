<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    protected $transport;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'columns' => $this->getColumns(),
            'data' =>
            [
                'id' => $this->id,
                'type' => 'report',
                'attributes' => $this->getAttributes($request),
            ],

        ];
    }

    public function getColumns(){
        $attributes = array_keys($this->getAttributes());

        return collect($attributes)->map(fn($attribute) => [
            'key' => $attribute,
            'name' => __('reports.fields.' . $attribute . '.name'),
            'alias' => __('reports.fields.' . $attribute . '.alias'),
        ])->toArray();


    }

//    public function getAttributes($request){
//        $this->getAttributes();
//    }
}
