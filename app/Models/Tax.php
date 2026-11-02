<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    public const TYPES = ['Fixed', 'Percentage'];
    protected $fillable = [
        'service_id',
        'name',
        'rate',
        'type',
        'is_active',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
    //
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}