<?php

namespace App\Report\Transport;

class TransportService
{
    public function parseTransport($attributes)
    {
        return Transport::create($attributes);
    }
}
