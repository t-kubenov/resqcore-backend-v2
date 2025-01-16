<?php

namespace App\Services\V2;

use App\Models\Transport;

class TransportService
{
    public function parseTransport($attributes)
    {
        return Transport::create($attributes);
    }
}
