<?php

namespace App\FieldOption;

use App\Document\Http\GenericRelationshipCollection;
use App\FieldOption\Support\FieldOptionContext;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FieldOptionController extends Controller
{
    public function __construct(
        private readonly FieldOptionService $service
    ){}

    public function getFieldOptions(string $fieldName, Request $request)
    {
        $context = FieldOptionContext::fromAuth();
        $search = $request->input('search');

        if ($fieldName === 'boolean') {
            return response()->json([
                'data' => [
                    ['id' => 1, 'name' => __('etc.boolean.true')],
                    ['id' => 0, 'name' => __('etc.boolean.false')],
                ],
            ]);
        }

        $options = $this->service->getOptions($fieldName, $search, $context);

        return new GenericRelationshipCollection($options);
    }
}
