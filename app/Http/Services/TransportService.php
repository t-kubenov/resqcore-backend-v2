<?php

namespace App\Http\Services;

use App\Models\Transport;

class TransportService
{
    public function parseTransport($attributes)
    {
        return Transport::create($attributes);
    }
}
