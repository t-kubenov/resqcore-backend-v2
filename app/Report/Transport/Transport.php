<?php

namespace App\Report\Transport;

use Illuminate\Database\Eloquent\Model;
use App\Report\Report;
use App\Report\Update\Update;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Transport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'update_id',
        'type',
        'count',
    ];

    public function transport_type()
    {
        return $this->belongsTo(TransportType::class, 'type');
    }

    public function getNameAttribute()
    {
        return $this->transport_type?->name;
    }

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function report_update()
    {
        return $this->belongsTo(Update::class);
    }
}
