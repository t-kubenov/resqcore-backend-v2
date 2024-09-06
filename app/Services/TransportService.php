<?php

namespace App\Services;

class TransportService
{
    public function parseTransport($attributes)
    {
        return Transport::create($attributes);
    }
}
